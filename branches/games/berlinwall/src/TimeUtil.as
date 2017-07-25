package{
	public class TimeUtil{
		public function TimeUtil(){
		}
		public static function getTime(sec:Number):String{
			//var sec:Number = Math.floor(ms/1000);
			var min:Number = Math.floor(sec/60);
			sec = sec - (60*min);
			var strsec:String = "";
			var strmin:String = "";
			if(sec<10){
				strsec=String("0"+String(sec));
			}else{
				strsec = String(sec);
			}
			if(min<10){
				strmin=String("0"+String(min));
			}else{
				strmin = String(min);
			}
			return String(strmin+" : "+strsec);
		}
		public function getSeconds(ms:Number):String{
			return String(ms/1000);
		}
	}
}