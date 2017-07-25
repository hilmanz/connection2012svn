<?php

class DailyReporting{
    protected $log;
    protected $conn;
    protected $date;
    protected $date_ts;
    protected $start_of_week;
    protected $start_of_week_ts;
    private $verbose = false;

    public function __construct($conn, $log){
        $this->conn = $conn;
        $this->log = $log;
    }

    public function date($date = null) {
        if (is_null($date)) {
            return $this->date;
        } else {
            $this->date = $date;
        }
    }

    public function date_ts($date_ts = null) {
        if (is_null($date_ts)) {
            return $this->date_ts;
        } else {
            $this->date_ts = $date_ts;
        }
    }

    public function run($verbose = false) {
        $this->verbose = $verbose;
        $this->determineFirstDayOfThisWeek();
        
        $this->prepareTemporarySocialMember();
        $this->prepareTemporaryActivityLog();
        $this->prepareTemporaryMerchandiseTransaction();

        $this->createDailyLoginReport();
        $this->createDailyLoginPerAgeCategoryReport();
        $this->createDailyCumulativeRegistrantReport();
        
        //only calculate after July 24th, 2012
        if (strcmp ( $this->date , "2012-07-23" ) > 0) {
            $this->createDailyParticipantReport();
        }
        
        $this->createDailyGenderReport();
        $this->createDailyCumulativeRedeemBadgeReport();
        $this->createRedeemBadgePerChannelReport();
        $this->createDailyRedeemMerchandiseReport();
    }
    
    private function determineFirstDayOfThisWeek() {
        $yesterday_dow = date('N', $this->date_ts );
        //print "yesterday_dow = $yesterday_dow\n";
        $this->start_of_week = $this->date;
        if ($yesterday_dow > 1) {
            //$start_of_week = date_sub($yesterday, date_interval_create_from_date_string(($yesterday_dow-1) . ' days'));
            $yesterday_inst = date_create($this->date);
            //var_dump($yesterday_inst);
            $this->start_of_week = date_format(date_sub($yesterday_inst, date_interval_create_from_date_string(($yesterday_dow-1) . ' days')), "Y-m-d");
        }
        $this->start_of_week_ts = strtotime($this->start_of_week);
        //print $start_of_week . "->" . date('N', strtotime($start_of_week) ) . "->" .strtotime($start_of_week) . "\n";
    }
    
    private function createDailyRedeemMerchandiseReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        
        
        $query = "REPLACE INTO $SCHEMA_REPORT.rp_merchandise_redeem_daily
        		  (date_d, merchandise_id, merchandise_name, merchandise_prefix, redeem_num)
        		  SELECT '" . $this->date . "', id, item_name, prefix_name, 0
        		  FROM $SCHEMA_CODE.merchandise_items";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert init report $SCHEMA_REPORT.rp_merchandise_redeem_daily",$rs);
        
        $query = "REPLACE INTO $SCHEMA_REPORT.rp_merchandise_redeem_daily
				  (date_d, merchandise_id, merchandise_name, merchandise_prefix, redeem_num)
				  SELECT '" . $this->date . "', B.id, B.item_name, B.prefix_name, A.num 
				  FROM (
				   SELECT prize, COUNT(id) num
				   FROM $SCHEMA_REPORT_TEMP.merchandise_transaction
				   WHERE request_date BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
				   GROUP BY prize) AS A
				  INNER JOIN $SCHEMA_CODE.merchandise_items AS B
				  ON A.prize = B.prefix_name";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert daily login report $SCHEMA_REPORT.rp_merchandise_redeem_daily",$rs);
    }

    private function prepareTemporaryMerchandiseTransaction() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->log->info("insert merchandise_transaction data as temp");

        if ($this->verbose) print "get last id from $SCHEMA_REPORT_TEMP.merchandise_transaction\n";
        $last_id = 0;
        $query = "SELECT id FROM $SCHEMA_REPORT_TEMP.merchandise_transaction
        		  ORDER BY id DESC";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $last_id = $row['id'];
        }
        mysql_free_result($rs);

        if ($this->verbose) print "Get all live data, insert to temporar\n";
        $query = "INSERT IGNORE INTO $SCHEMA_REPORT_TEMP.merchandise_transaction
				  (id, user_id, redeemed_date, badge_id, prize, transaction_id, redeem_id, redeem_time)
				  SELECT id, user_id, redeemed_date, badge_id, prize, transaction_id, redeem_id, redeem_time
				  FROM $SCHEMA_CODE.merchandise_transaction
				  WHERE id > $last_id AND request_date <= '" . $this->date . " 23:59:59'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.merchandise_transaction",$rs);
    }

    private function createRedeemBadgePerChannelReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;
        
        $this->prepareBadgeCode();
        $this->prepareBadgeRedeem();
        
        if ($this->verbose) print "init -badge-per-channel data\n";
        $query = "TRUNCATE $SCHEMA_REPORT_TEMP.badge_channel_redeem";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $query = "REPLACE INTO $SCHEMA_REPORT_TEMP.badge_channel_redeem
                  (channel_id,channel_name,redeem_num,processing_time)
                  SELECT channel_id, channel_name, 0, NOW() 
                  FROM $SCHEMA_CODE.badge_channel";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("init -badge-per-channel TEMP $SCHEMA_REPORT_TEMP.badge_channel_redeem",$rs);
        
        
        if ($this->verbose) print "get yesterday badge per channel $SCHEMA_REPORT.rp_badge_channel_daily\n";
        $query = "REPLACE INTO $SCHEMA_REPORT_TEMP.badge_channel_redeem
        		  (channel_id,channel_name,redeem_num,processing_time)
        	      SELECT channel_id, channel_name, redeem_num, NOW()
                  FROM $SCHEMA_REPORT.rp_badge_channel_daily
                  WHERE date_d = DATE_SUB('" . $this->date . "', INTERVAL 1 DAY)";
        $rs = mysql_query($query, $this->conn);
        if ($this->verbose) {print $query . "\n";}
        $this->log->status("insert yesterday data TEMP $SCHEMA_REPORT_TEMP.rp_badge_channel_daily",$rs);
        
        if ($this->verbose) print "get increment badge per channel $SCHEMA_REPORT.badge_channel_redeem\n";
        $query = "INSERT INTO $SCHEMA_REPORT_TEMP.badge_channel_redeem
        		  (channel_id,channel_name,redeem_num,processing_time)
        		  SELECT channel, '', COUNT(id) num, NOW()
                  FROM $SCHEMA_REPORT_TEMP.badge_redeem AS A
                  INNER JOIN $SCHEMA_REPORT_TEMP.badge_code AS B 
                  ON (A.kode = B.kode)
                  WHERE redeem_time BETWEEN '" . $this->date . " 00:00:00' AND '" . $this->date . " 23:59:59'
                  GROUP BY channel
                  ON DUPLICATE KEY UPDATE redeem_num = redeem_num + VALUES(redeem_num)";
        $rs = mysql_query($query, $this->conn);
        if ($this->verbose) {print $query . "\n";}
        if ($this->verbose) print "increase badge per channel number $SCHEMA_REPORT.badge_channel_redeem\n";
        
        if ($this->verbose) print "insert badge per channel report $SCHEMA_REPORT.rp_badge_channel_daily\n";
        $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_channel_daily
                  (date_d, channel_id, channel_name, redeem_num) 
                  SELECT '" . $this->date . "', channel_id, channel_name, redeem_num
				  FROM $SCHEMA_REPORT_TEMP.badge_channel_redeem";
        $rs = mysql_query($query, $this->conn); 
        if ($this->verbose) {print $query . "\n";}
        if ($this->verbose) print "insert into $SCHEMA_REPORT.rp_badge_channel_daily\n";
    }
    
    private function prepareBadgeCode() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->log->info("insert badge_code data as temp");

        if ($this->verbose) print "get last id from $SCHEMA_REPORT_TEMP.badge_redeem\n";
        $generated_date = '0000-00-00 00:00:00';
        $query = "SELECT generated_date FROM $SCHEMA_REPORT_TEMP.badge_code
        		  ORDER BY generated_date DESC";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $generated_date = $row['generated_date'];
        }
        mysql_free_result($rs);

        if ($this->verbose) print "Get all badge redeem data, insert to temporar\n";
        $query = "REPLACE INTO $SCHEMA_REPORT_TEMP.badge_code
                  (kode, channel, tier, is_wildcard, is_used, TYPE, generated_date, 
                   n_status, location, start_date, end_date, description)
				  SELECT kode, channel, tier, is_wildcard, is_used, TYPE, generated_date,
				   n_status, location, start_date, end_date, description
				  FROM $SCHEMA_CODE.badge_code
				  WHERE generated_date >= '" . $generated_date . "'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.badge_code",$rs);
    }

    private function prepareBadgeRedeem() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->log->info("insert badge_redeem data as temp");

        if ($this->verbose) print "get last id from $SCHEMA_REPORT_TEMP.badge_redeem\n";
        $last_id = 0;
        $query = "SELECT id FROM $SCHEMA_REPORT_TEMP.badge_redeem
        		  ORDER BY id DESC";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $last_id = $row['id'];
        }
        mysql_free_result($rs);

        if ($this->verbose) print "Get all badge redeem data, insert to temporar\n";
        $query = "INSERT IGNORE INTO $SCHEMA_REPORT_TEMP.badge_redeem
				  (id, user_id, redeem_time, kode)
				  SELECT id, user_id, redeem_time, kode
				  FROM $SCHEMA_CODE.badge_redeem
				  WHERE id > $last_id AND redeem_time <= '" . $this->date . " 23:59:59'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.badge_redeem",$rs);
    }

    private function createDailyCumulativeRedeemBadgeReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->prepareBadgeInventoryLog();

        $total_num = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA_REPORT_TEMP.badge_inventory
				  WHERE redeem_time <= '" . $this->date . " 23:59:59'";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $total_num = $row['num'];
        }
        mysql_free_result($rs);

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_redeem_daily
				  (date_d, badge_id, badge_name)
                  SELECT '" . $this->date . "', id, NAME 
                  FROM $SCHEMA_CODE.badge_catalog";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert init report $SCHEMA_REPORT.rp_badge_redeem_daily",$rs);

        if ($total_num > 0) {
            $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_redeem_daily
				  	  (date_d, badge_id, badge_name, redeem_num, redeem_total, redeem_percent)
                  	  SELECT '" . $this->date . "', A.badge_id, B.name, A.num, $total_num, A.num*100/$total_num
                      FROM (
                      SELECT badge_id, COUNT(id) num
                      FROM $SCHEMA_REPORT_TEMP.badge_inventory
                      WHERE redeem_time <= '" . $this->date . " 23:59:59'
                      GROUP BY badge_id ) AS A
                      INNER JOIN $SCHEMA_CODE.badge_catalog AS B
                      ON A.badge_id = B.id";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert daily login report $SCHEMA_REPORT.rp_badge_redeem_daily",$rs);
        }
    }

    private function prepareBadgeInventoryLog() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->log->info("insert badge_inventory data as temp");

        if ($this->verbose) print "get last id from $SCHEMA_REPORT_TEMP.badge_inventory\n";
        $last_id = 0;
        $query = "SELECT id FROM $SCHEMA_REPORT_TEMP.badge_inventory
        		  ORDER BY id DESC";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $last_id = $row['id'];
        }
        mysql_free_result($rs);

        if ($this->verbose) print "Get all badge inventory data, insert to temporar\n";
        $query = "INSERT IGNORE INTO $SCHEMA_REPORT_TEMP.badge_inventory
				  (id, user_id, redeem_time, badge_id, redeem_id)
				  SELECT id, user_id, redeem_time, badge_id, redeem_id
				  FROM $SCHEMA_CODE.badge_inventory
				  WHERE id > $last_id AND redeem_time <= '" . $this->date . " 23:59:59'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.badge_inventory",$rs);
    }

    private function createDailyGenderReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_overall_gender_daily
				  (date_d, gender, login_num) VALUES 
				  ('" . $this->date . "','M',0),
				  ('" . $this->date . "','F',0)";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert init report $SCHEMA_REPORT.rp_overall_gender_daily",$rs);

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_overall_gender_daily
				  (date_d, gender, login_num)
				  SELECT '" . $this->date . "', sex, COUNT(id)
				  FROM $SCHEMA_REPORT_TEMP.social_member
				  WHERE n_status = 1 AND register_date <= '" . $this->date . " 23:59:59'
				  GROUP BY sex";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert daily login report $SCHEMA_REPORT.rp_overall_gender_daily",$rs);
    }
    
    private function prepareTemporarySocialMember() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->log->info("truncate temporary social_member");

        $query = "TRUNCATE $SCHEMA_REPORT_TEMP.social_member";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("truncate TEMP $SCHEMA_REPORT_TEMP.social_member",$rs);
        
        $this->log->info("insert social_member data as temp");
        $query = "INSERT INTO $SCHEMA_REPORT_TEMP.social_member
                  (id, register_id, NAME, email, register_date, img, small_img, username, TYPE,
                   last_login, city, sex, birthday, description, last_name, StreetName, MobilePhone,
                   n_status, login_count, mobile_type, Brand1_ID)
                  SELECT id, register_id, NAME, email, register_date, img, small_img, username, TYPE,
                   last_login, city, sex, birthday, description, last_name, StreetName, MobilePhone,
                   n_status, login_count, mobile_type, Brand1_ID
                  FROM $SCHEMA.social_member";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.social_member",$rs);

        $query = "UPDATE $SCHEMA_REPORT_TEMP.social_member SET age_cat = 1
                  WHERE YEAR(CURRENT_DATE)-YEAR(birthday) BETWEEN 19 AND 29";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update age_cat 1 TEMP $SCHEMA_REPORT_TEMP.social_member",$rs);

        $query = "UPDATE $SCHEMA_REPORT_TEMP.social_member SET age_cat = 2
                  WHERE YEAR(CURRENT_DATE)-YEAR(birthday) BETWEEN 30 AND 35";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update age_cat 2 TEMP $SCHEMA_REPORT_TEMP.social_member",$rs);
        
        $query = "UPDATE $SCHEMA_REPORT_TEMP.social_member SET age_cat = 1
                  WHERE YEAR(CURRENT_DATE)-YEAR(birthday) > 35";
        //if ($this->verbose) {print $query . "\n";}
        //$rs = mysql_query($query, $this->conn);
        //$this->log->status("update age_cat 3 TEMP $SCHEMA_REPORT_TEMP.social_member",$rs);
    }

    private function createDailyParticipantReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        if ($this->verbose) {print "calculate total participant\n";}
        $total_participant = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA_REPORT_TEMP.social_member
                  WHERE n_status = 1 AND register_date <= '" . $this->date . " 23:59:59'
                  AND (register_date <= '2012-07-24 23:59:59' OR (register_date > '2012-07-24 23:59:59' AND register_id <> 0))";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $total_participant = $row['num'];
        }
        mysql_free_result($rs);
        if ($this->verbose) {print "total participant = $total_participant\n";}
                  
        if ($this->verbose) {print "calculate existing participant\n";}
        $existing_participant = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA_REPORT_TEMP.social_member
                  WHERE n_status = 1 AND register_date <= '" . $this->date . " 23:59:59'
                  AND (register_date <= '2012-07-24 23:59:59' OR (register_date > '2012-07-24 23:59:59' AND register_id <> 0))
                  AND 
                    ( email IN (SELECT email FROM $SCHEMA_REPORT.tbl_existing_user)
                      OR
                      register_date < '" . $this->start_of_week . " 00:00:00'
                    )";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $existing_participant = $row['num'];
        }
        mysql_free_result($rs);
        if ($this->verbose) {print "existing participant = $existing_participant\n";}
        
        $new_participant = $total_participant - $existing_participant;
                  
        $query = "UPDATE $SCHEMA_REPORT.rp_overall_user_daily
                  SET new_num = '$new_participant', existing_num = '$existing_participant'
                  WHERE date_d = '" . $this->date . "'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update cumulative new participant daily login report $SCHEMA_REPORT.rp_overall_user_daily",$rs);    
    }
    
    private function createDailyExistingParticipantReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $new_participant = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA.social_member
                  WHERE register_date <= '" . $this->date . " 23:59:59'
                  SELECT COUNT(id) num FROM $SCHEMA.social_member
                  WHERE n_status = 1 AND register_date <= '" . $this->date . " 23:59:59'
                  AND (register_date <= '2012-07-24 23:59:59' 
                       OR (register_date > '2012-07-24 23:59:59' AND register_id <> 0))";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $new_participant = $row['num'];
        }
        mysql_free_result($rs);
                  
        $query = "UPDATE $SCHEMA_REPORT.rp_overall_user_daily
                  SET new_num = '$new_participant'
                  WHERE date_d = '" . $this->date . "'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update cumulative new participant daily login report $SCHEMA_REPORT.rp_overall_user_daily",$rs);    
    }
    
    private function createDailyNewParticipantReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $new_participant = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA.social_member
                  WHERE register_date <= '" . $this->date . " 23:59:59'
                  SELECT COUNT(id) num FROM $SCHEMA.social_member
                  WHERE n_status = 1 AND register_date <= '" . $this->date . " 23:59:59'
                  AND (register_date <= '2012-07-24 23:59:59' 
                       OR (register_date > '2012-07-24 23:59:59' AND register_id <> 0))";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $new_participant = $row['num'];
        }
        mysql_free_result($rs);
                  
        $query = "UPDATE $SCHEMA_REPORT.rp_overall_user_daily
                  SET new_num = '$new_participant'
                  WHERE date_d = '" . $this->date . "'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update cumulative new participant daily login report $SCHEMA_REPORT.rp_overall_user_daily",$rs);    
    }
    
    private function createDailyCumulativeRegistrantReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $total_registrant = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA_REPORT_TEMP.social_member
                  WHERE register_date <= '" . $this->date . " 23:59:59'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $total_registrant = $row['num'];
        }
        mysql_free_result($rs);
                  
        $query = "UPDATE $SCHEMA_REPORT.rp_overall_user_daily
                  SET registrant_num = '$total_registrant'
                  WHERE date_d = '" . $this->date . "'";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update cumulative registrant daily login report $SCHEMA_REPORT.rp_overall_user_daily",$rs);    
    }

    private function createDailyLoginReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_overall_user_daily (date_d, login_num)
                  VALUES ('" . $this->date . "', 0)";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert init daily login $SCHEMA_REPORT.rp_overall_user_daily",$rs);
        
        $query = "REPLACE INTO $SCHEMA_REPORT.rp_overall_user_daily (date_d, login_num)
                  SELECT '" . $this->date . "', COUNT( id ) 
				  FROM $SCHEMA_REPORT_TEMP.tbl_activity_log
                  WHERE date_ts >= " . $this->date_ts . " AND date_ts < " . ($this->date_ts+86400) . " 
                  AND action_id = 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert daily login report $SCHEMA_REPORT.rp_overall_user_daily",$rs);
    }

    private function createDailyLoginPerAgeCategoryReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_overall_age_daily
                  (date_d, age_category, login_num)VALUES
                  ('" . $this->date . "','1','0'),('" . $this->date . "','2','0'),('" . $this->date . "','3','0')";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert init daily login $SCHEMA_REPORT.rp_overall_age_daily",$rs);
        
        $query = "REPLACE INTO $SCHEMA_REPORT.rp_overall_age_daily (date_d, age_category, login_num)
                  SELECT '" . $this->date . "', age_category, COUNT( id ) 
				  FROM $SCHEMA_REPORT_TEMP.tbl_activity_log
                  WHERE date_ts >= " . $this->date_ts . " AND date_ts < " . ($this->date_ts+86400) . " 
                  AND action_id = 1
                  GROUP BY age_category";
        //print $query . "\n";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert daily login report $SCHEMA_REPORT.rp_overall_age_daily",$rs);
    }

    private function prepareTemporaryActivityLog() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->log->info("insert tbl_activity_log data as temp");

        if ($this->verbose) print "get last id from $SCHEMA_REPORT_TEMP.tbl_activity_log\n";
        $last_id = 0;
        $query = "SELECT id FROM $SCHEMA_REPORT_TEMP.tbl_activity_log
        		  ORDER BY id DESC";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $last_id = $row['id'];
        }
        mysql_free_result($rs);

        if ($this->verbose) print "Get all live data, insert to temporar\n";
        $query = "INSERT IGNORE INTO $SCHEMA_REPORT_TEMP.tbl_activity_log
				  (id, user_id, date_ts, date_time, action_id, action_value)
				  SELECT id, user_id, date_ts, date_time, action_id, action_value
				  FROM $SCHEMA.tbl_activity_log
				  WHERE id > $last_id AND date_ts < " . ($this->date_ts + 86400);
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.tbl_activity_log",$rs);
        
        if ($this->verbose) print "update age_cateogry in temporar\n";
        $query = "UPDATE $SCHEMA_REPORT_TEMP.tbl_activity_log AS A
                  INNER JOIN $SCHEMA_REPORT_TEMP.social_member AS B 
                  ON (A.user_id = B.id)
                  SET A.age_category =  B.age_cat
                  WHERE A.age_category = 0";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("update age_category TEMP $SCHEMA_REPORT_TEMP.tbl_activity_log",$rs);
        
    }

}

?>