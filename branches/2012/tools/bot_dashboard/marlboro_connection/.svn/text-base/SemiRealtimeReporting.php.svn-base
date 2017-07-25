<?php

class SemiRealtimeReporting{
    protected $log;
    protected $conn;
    protected $date;
    protected $date_ts;
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
        
        $this->prepareBadgeInventory();
        $this->createSingleBadgeReport();
        $this->prepareBadgeRedeem();
        $this->prepareBadgeCode();
        $this->createBadgeSetReport();
        $this->prepareAuctionPost();
        $this->createBadgesListReport();
        $this->createBadgesBeingTradedReport();
        $this->createTotalTradingReport();
    }
    
    private function createTotalTradingReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        if ($this->verbose) print "get the number of data from $SCHEMA_REPORT.rp_badge_trading_summary_realtime\n";
        $data_num = 0;
        $query = "SELECT COUNT(*) num FROM $SCHEMA_REPORT.rp_badge_trading_summary_realtime";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $data_num = $row['num'];
        }
        mysql_free_result($rs);
        
        if ($data_num == 0) {
            $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_trading_summary_realtime
                      (total_trade,successful_trade,last_update)VALUES(0,0,NOW())";
            if ($this->verbose) {print $query . "\n";}
            $rs = mysql_query($query, $this->conn);
            $this->log->status("insert single badge report $SCHEMA_REPORT.rp_badge_trading_summary_realtime",$rs);            
        }

        if ($this->verbose) print "get the number of total trading from $SCHEMA_REPORT.rp_badge_trading_summary_realtime\n";
        $total_trade = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA_REPORT_TEMP.auction_post";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $total_trade = $row['num'];
        }
        mysql_free_result($rs);
        
        if ($this->verbose) print "get the number of total successful trading from $SCHEMA_REPORT.rp_badge_trading_summary_realtime\n";
        $successful_trade = 0;
        $query = "SELECT COUNT(id) num FROM $SCHEMA_REPORT_TEMP.auction_post WHERE n_status = 1";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $successful_trade = $row['num'];
        }
        mysql_free_result($rs);
        
        $query = "UPDATE $SCHEMA_REPORT.rp_badge_trading_summary_realtime
                  SET total_trade = '$total_trade', successful_trade = '$successful_trade', last_update = NOW()";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert summary report $SCHEMA_REPORT.rp_badge_trading_summary_realtime",$rs);    
    }
    
    private function createBadgesBeingTradedReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_trading_ongoing_realtime
                  (badge_id, badge_name, trading_num, update_time)
                  SELECT M.badge_id, N.name, M.num, NOW() FROM (
                    SELECT badge_id, SUM(num) num FROM (
                    SELECT need_id AS badge_id, COUNT(id) num
                    FROM $SCHEMA_REPORT_TEMP.auction_post
                    WHERE n_status = 0
                    GROUP BY need_id
                    UNION ALL
                    SELECT with_id AS badge_id, COUNT(id) num
                    FROM $SCHEMA_REPORT_TEMP.auction_post
                    WHERE n_status = 0
                    GROUP BY with_id ) AS A
                  GROUP BY badge_id ) AS M
                  INNER JOIN $SCHEMA_CODE.badge_catalog AS N
                  ON M.badge_id = N.id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert single badge report $SCHEMA_REPORT.rp_badge_trading_ongoing_realtime",$rs);
    }
        
    private function createBadgesListReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_trading_finished_realtime
                  (badge_id, badge_name, trading_num, update_time)
                  SELECT M.badge_id, N.name, M.num, NOW() FROM (
                    SELECT badge_id, SUM(num) num FROM (
                    SELECT need_id AS badge_id, COUNT(id) num
                    FROM $SCHEMA_REPORT_TEMP.auction_post
                    WHERE n_status = 1
                    GROUP BY need_id
                    UNION ALL
                    SELECT with_id AS badge_id, COUNT(id) num
                    FROM $SCHEMA_REPORT_TEMP.auction_post
                    WHERE n_status = 1
                    GROUP BY with_id ) AS A
                  GROUP BY badge_id ) AS M
                  INNER JOIN $SCHEMA_CODE.badge_catalog AS N
                  ON M.badge_id = N.id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert single badge report $SCHEMA_REPORT.rp_badge_trading_finished_realtime",$rs);
    }
        
    private function prepareAuctionPost() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $this->log->info("insert auction_post data as temp");

        if ($this->verbose) print "get last id from $SCHEMA_REPORT_TEMP.auction_post\n";
        $last_id = 0;
        $query = "SELECT id FROM $SCHEMA_REPORT_TEMP.auction_post
                  ORDER BY id DESC";
        $rs = mysql_query($query, $this->conn);
        if ($row = mysql_fetch_assoc($rs)) {
            $last_id = $row['id'];
        }
        mysql_free_result($rs);

        if ($this->verbose) print "Get finished auction_post data, insert to temporar\n";
        $query = "REPLACE INTO $SCHEMA_REPORT_TEMP.auction_post
                  (id, user_id, need_id, with_id, posted_date, closed_time, n_status)
                  SELECT B.id, B.user_id, B.need_id, B.with_id, B.posted_date, B.closed_time, B.n_status
                  FROM $SCHEMA_REPORT_TEMP.auction_post AS A
                  INNER JOIN $SCHEMA_CODE.auction_post AS B
                  ON A.id = B.id
                  WHERE A.n_status = 0 AND B.n_status = 1";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.auction_post",$rs);
        
        if ($this->verbose) print "Get new auction_post data, insert to temporar\n";
        $query = "INSERT INTO $SCHEMA_REPORT_TEMP.auction_post
                  (id, user_id, need_id, with_id, posted_date, closed_time, n_status)
                  SELECT id, user_id, need_id, with_id, posted_date, closed_time, n_status
                  FROM $SCHEMA_CODE.auction_post
                  WHERE id > $last_id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.auction_post",$rs);
    }

    private function createBadgeSetReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_set_realtime
                  (set_id, set_name, redeem_num, update_time)
                  SELECT N.id, N.name, M.num, NOW()  FROM (
                    SELECT tier, COUNT(id) num
                    FROM $SCHEMA_REPORT_TEMP.badge_redeem AS A
                    INNER JOIN $SCHEMA_REPORT_TEMP.badge_code AS B 
                    ON (A.kode = B.kode)
                    GROUP BY tier ) AS M
                  INNER JOIN $SCHEMA_CODE.badge_series AS N
                  ON M.tier = N.id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert single badge report $SCHEMA_REPORT.rp_badge_set_realtime",$rs);
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
        $query = "INSERT IGNORE INTO $SCHEMA_REPORT_TEMP.badge_code
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
                  WHERE id > $last_id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.badge_redeem",$rs);
    }
    
    private function createSingleBadgeReport() {
        global $SCHEMA, $SCHEMA_CODE, $SCHEMA_REPORT, $SCHEMA_REPORT_TEMP;

        $query = "REPLACE INTO $SCHEMA_REPORT.rp_badge_single_realtime
                  (badge_id, badge_name, redeem_num, update_time)
                  SELECT A.badge_id, B.name, A.num, NOW()
                  FROM (
                   SELECT badge_id, COUNT(id) num
                   FROM $SCHEMA_REPORT_TEMP.badge_inventory
                   GROUP BY badge_id ) AS A
                  INNER JOIN $SCHEMA_CODE.badge_catalog AS B
                  ON A.badge_id = B.id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert single badge report $SCHEMA_REPORT.rp_badge_single_realtime",$rs);
    }

    private function prepareBadgeInventory() {
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
                  WHERE id > $last_id";
        if ($this->verbose) {print $query . "\n";}
        $rs = mysql_query($query, $this->conn);
        $this->log->status("insert TEMP $SCHEMA_REPORT_TEMP.badge_inventory",$rs);
    }

}

?>