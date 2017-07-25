package 
{
	import br.com.stimuli.loading.BulkLoader;
	import br.com.stimuli.loading.BulkProgressEvent;
	import br.com.stimuli.loading.loadingtypes.ImageItem;
	import com.pblabs.engine.core.IPBObject;
	import com.pblabs.engine.core.LevelManager;
	import com.pblabs.engine.core.PBGroup;
	import com.pblabs.engine.debug.Stats;
	import com.pblabs.engine.entity.IEntity;
	import com.pblabs.engine.entity.IEntityComponent;
	import com.pblabs.engine.entity.PropertyReference;
	import com.pblabs.engine.resource.MP3Resource;
	import com.pblabs.engine.resource.ResourceEvent;
	import com.pblabs.engine.resource.SoundResource;
	import com.pblabs.engine.resource.SWFResource;
	import com.pblabs.rendering2D.DisplayObjectRenderer;
	import com.pblabs.rendering2D.modifier.BorderModifier;
	import com.pblabs.rendering2D.MovieClipRenderer;
	import com.pblabs.rendering2D.spritesheet.SpriteSheetComponent;
	import com.pblabs.rendering2D.SpriteSheetRenderer;
	import com.pblabs.screens.BaseScreen;
	import com.pblabs.screens.ImageScreen;
	import com.pblabs.screens.ScreenManager;
	import com.pblabs.screens.SplashScreen;
	import com.pblabs.sound.BackgroundMusicComponent;
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.events.NetStatusEvent;
	import flash.events.ProgressEvent;
	import flash.geom.Point;
	import flash.net.NetConnection;
	import flash.net.ObjectEncoding;
	import flash.net.Responder;
	
	import com.pblabs.rendering2D.modifier.BlurModifier;
	import com.pblabs.rendering2D.SimpleSpatialComponent;
	
	import com.pblabs.rendering2D.SpriteRenderer;
	import com.pblabs.rendering2D.ui.SceneView;
	import com.pblabs.rendering2D.spritesheet.CellCountDivider;
	import com.pblabs.engine.core.LevelEvent;
	import com.pblabs.animation.AnimatorComponent;
	
	import flash.display.Sprite;
	import com.greensock.*;
	import com.greensock.easing.*;
	import mx.rpc.remoting.mxml.*;
	
	// PushButton Engine Imports
	import com.pblabs.engine.PBE;
	import com.pblabs.engine.debug.Logger;
	import com.pblabs.engine.core.ProcessManager;
	
	[SWF(width = "798", height = "564", backgroundColor = "0x00000000", frameRate = "100")]
	[Frame( factoryClass = "Preloader")]
	public class Main extends Sprite
	{
		private var landing:mc_landing;
		private var current_level:int = 1;
		private var gameui:mc_ui;
		private var sound:SoundResource;
		private var howto:mc_howto;
		private var user_info:Object;
		private var piring:mc_piring;
		private var lv1:mc_lv1go;
		private var lv2:mc_lv2go;
		private var lv3:mc_lv3go;
		private var win:mc_win;
		private var lose:mc_lose;
		private var is_win:Boolean = false;
		private var flashvars:Object;
		private var badges:Array;
		private var badge_chance:Array = [0.3, 0.3, 0.1, 0.1, 0.1, 0.05, 0.05];
		public var user_last_level:int = 1; //artinya newbie. belum main sama sekali
		private var user_id:String = "1";
		private var badge_id:Number = 0;
		private var remote_url:String;
		
		
		public function Main() {
			//trace("MAIN");
			if (stage) {
				ready(null);
			}else {
				addEventListener(Event.ADDED_TO_STAGE, ready, false, 0, true);
			}
			
		}	
		private function ready(evt:Event):void {
			user_info = { };
			
			PBE.registerType(com.pblabs.rendering2D.SpriteRenderer);
			PBE.registerType(com.pblabs.rendering2D.SimpleSpatialComponent);
			PBE.registerType(com.pblabs.rendering2D.modifier.BlurModifier);
			PBE.registerType(com.pblabs.rendering2D.SpriteSheetRenderer);
			PBE.registerType(com.pblabs.rendering2D.spritesheet.SpriteSheetComponent);
			PBE.registerType(com.pblabs.rendering2D.spritesheet.CellCountDivider);
			PBE.registerType(com.pblabs.rendering2D.MovieClipRenderer);
			
			PBE.registerType(com.pblabs.animation.AnimatorComponent);
			PBE.registerType(GameControllerComponent);
			PBE.registerType(GameTickComponent);
			PBE.registerType(ConfigComponent);
			PBE.registerType(com.pblabs.sound.BackgroundMusicComponent);
			PBE.registerType(Level1);
			PBE.registerType(Level2);
			PBE.registerType(Level3);
			PBE.registerType(ItemStatus);
			// Initialize the engine!
			PBE.startup(this);
			
			PBE.addResources(new GameResources());
			PBE.mainStage.loaderInfo.addEventListener(ProgressEvent.PROGRESS, onLoadingProgress, false, 0, true);
			PBE.mainStage.addEventListener("SCORE_UPDATED", onScoreUpdated, false, 0, true);
			
			// Set up the scene view.
			var sv:SceneView = new SceneView();
			flashvars = PBE.getFlashVars();
			try {
				user_id = flashvars.user_id;
			}catch(e:Error){
				user_id = "0";
			}
			PBE.initializeScene(sv);
			 initEverything();
			 //init_config();
			
		}
		
		private function onNewLevel(e:Event):void 
		{
			var game:IEntity = PBE.lookupEntity("core");
			gameui.txt_level.text = "Level " + game.getProperty(new PropertyReference("@level.level"));
			gameui.txt_song.text = game.getProperty(new PropertyReference("@level.song_title"));
		}
		
		private function onScoreUpdated(e:Event):void 
		{
			//trace("SCORE UPDATED");
			var game:IEntity = PBE.lookupEntity("core");
			var score:Number = game.getProperty(new PropertyReference("@level.total_score"));
			var max_score:Number = game.getProperty(new PropertyReference("@level.max_score"));
			if (score < 0) {
				score = 0;
			}
			var cuePos:Number = 127 * (score / max_score);
			if (cuePos > 127) {
				cuePos = 127;
			}
			trace(score);
			//trace(cuePos);
			gameui.meter.cue.y = 127-cuePos;
		}
		private function get_badges():int{
			var winner:int = 0;
			var highest:Number = 0;
			var n:int = 0;
			for each(var i:Number in badge_chance) {
				var roll:Number = Math.round(Math.random() * 12) /12;
				var weight:Number = i + roll;
				if (weight > highest) {
					winner = n;
					highest = weight;
				}
				//trace(n+" -> "+weight+ " "+i+"+ roll: "+roll);
				n++;
			}
			winner += 1;
			return winner;
		}
		private function onLoadingProgress(e:ProgressEvent):void 
		{
		//	trace("YEY");
			trace(e.bytesLoaded + " / " + e.bytesTotal);
		}
		
		private function initEverything():void 
		{
			
			
			LevelManager.instance.addEventListener(LevelEvent.LEVEL_LOADED_EVENT, onLevelLoaded, false, 0, true);
			LevelManager.instance.load("../assets2/LevelDescriptions.xml", 0);
			
			PBE.mainStage.addEventListener("LEVEL_COMPLETE", onLevelCompleted, false, 0, true);
			PBE.mainStage.addEventListener("SKIPPED", onSkipped, false, 0, true);
		}
		
		
		
		private function show_badge(_badges:int):void 
		{
			win.b1.visible = false;
			win.b2.visible = false;
			win.b3.visible = false;
			win.b4.visible = false;
			win.b5.visible = false;
			win.b6.visible = false;
			win.b7.visible = false;
			
			switch(_badges) {
				case 1:
					win.b1.visible = true;
				break;
				case 2:
					win.b2.visible = true;
				break;
				case 3:
					win.b3.visible = true;
				break;
				case 4:
					win.b4.visible = true;
				break;
				case 5:
					win.b5.visible = true;
				break;
				case 6:
					win.b6.visible = true;
				break;
				case 7:
					win.b7.visible = true;
				break;
			}
			if (_badges > 0) {
				save_badge(user_id, _badges);
			}else {
				game_to_result_transition();
			}
			//win[_badges].visible = true;
		}
		
		private function onLevelCompleted(e:Event):void 
		{
			var game:IEntity = PBE.lookupEntity("core");
			var score:Number = game.getProperty(new PropertyReference("@level.total_score"));
			var level:Number = game.getProperty(new PropertyReference("@level.level"));
			var max_score:Number = game.getProperty(new PropertyReference("@level.max_score"));
			
			
			if (score > (max_score * 0.75)) {
				is_win = true;
				save_score(user_id, score, level);
				//var badge_id:int = get_badges();
				PBE.log(this, "last level : " + user_last_level);
				PBE.log(this, "current level : " + level);
				win.txt2.visible = false;
				win.title2.visible = false;
				win.btn2.visible = false;
				win.btn.visible = true;
				
				if (level == 3) {
					//trace("level terakhir");
					win.btn2.visible = true;
					win.btn.visible = false;
				}else {
					//trace("level bukan 3");
				}
				if(user_last_level<level){
					show_badge(get_badges());
					
					win.txt1.visible = true;
					win.title1.visible = true;
					win.txt2.visible = false;
					win.title2.visible = false;
					user_last_level = level;
					PBE.log(this, "get a badge");
				}else {
					show_badge(0);
					win.txt2.visible = true;
					win.title2.visible = true;
					win.txt1.visible = false;
					win.title1.visible = false;
					PBE.log(this, "no badge");
				}
				
				current_level += 1;
				if (current_level > 3) {
					current_level = 1;
					
				}
			}else {
				is_win = false;
				game_to_result_transition();
			}
			LevelManager.instance.loadLevel(8);
			gameui.visible = false;
			
		}
		private function onSkipped(e:Event):void 
		{
			var game:IEntity = PBE.lookupEntity("core");
			var score:Number = game.getProperty(new PropertyReference("@level.total_score"));
			var level:Number = game.getProperty(new PropertyReference("@level.level"));
			score = 501;
			if (score > 500) {
				is_win = true;
				save_score(user_id, score, level);
				//var badge_id:int = get_badges();
				PBE.log(this, "last level : " + user_last_level);
				PBE.log(this, "current level : " + level);
				win.txt2.visible = false;
				win.title2.visible = false;
				win.btn2.visible = false;
				win.btn.visible = true;
				
				if (level == 3) {
					//trace("level terakhir");
					win.btn2.visible = true;
					win.btn.visible = false;
				}else {
					//trace("level bukan 3");
				}
				if(user_last_level<level){
					show_badge(get_badges());
					
					win.txt1.visible = true;
					win.title1.visible = true;
					win.txt2.visible = false;
					win.title2.visible = false;
					user_last_level = level;
					PBE.log(this, "get a badge");
				}else {
					show_badge(0);
					win.txt2.visible = true;
					win.title2.visible = true;
					win.txt1.visible = false;
					win.title1.visible = false;
					PBE.log(this, "no badge");
				}
				
				current_level += 1;
				if (current_level > 3) {
					current_level = 1;
					
				}
			}else {
				is_win = false;
				game_to_result_transition();
			}
			LevelManager.instance.loadLevel(8);
			gameui.visible = false;
			//game_to_result_transition();
		}
		private function howto_transation():void {
			reset_piring();
			howto.alpha = 0;
			piring.visible = true;
			TweenLite.to(piring, 1, { x: -820, y:199 } );
			TweenLite.to(landing, 1, { alpha:0.0 } );
			TweenLite.to(howto, 1.5, { alpha:1.0} );
		
		}
		//transitions
		private function new_level_transition():void {
			reset_piring();
			piring.visible = true;
			if (current_level == 1) {
				anim_level_go(lv1);
			}
			if (current_level == 2) {
				anim_level_go(lv2);
			}
			if (current_level == 3) {
				anim_level_go(lv3);
			}
			TweenLite.to(piring, 1, { x: -820, y:199 } );
			TweenLite.to(landing, 1, { alpha:0.0 } );
			TweenLite.to(howto, 1, { alpha:0.0} );
		}
		private function game_to_result_transition():void {
			reset_piring();
			howto.alpha = 0;
			piring.visible = true;
			TweenLite.to(piring, 1, { x: -820, y:199 } );
			TweenLite.to(landing, 1, { alpha:0.0 } );
			
			if (is_win) {
				lose.visible = false;
				win.alpha = 0.0;
				win.visible = true;
				TweenLite.to(win, 1, { alpha:1.0 } );
			}else {
				win.visible = false;
				lose.alpha = 0.0;
				lose.visible = true;
				TweenLite.to(lose, 1, { alpha:1.0 } );
			}
		}
		private function result_to_game_transition():void {
			reset_piring();
			piring.visible = true;
			TweenLite.to(piring, 1, { x: -820, y:199 });
			TweenLite.to(win, 1, { alpha:0.0 } );
			TweenLite.to(lose, 1, { alpha:0.0 } );
			play_game();
		}
		//---end of transitions
		
		private function save_badge(user_id:String,badge_id:int):void {
			PBE.log(this, "connecting to " + remote_url);
			var conn:NetConnection = new NetConnection();
			conn.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus, false, 0, true);
			conn.objectEncoding = ObjectEncoding.AMF3;
			conn.connect(remote_url);
			var responder:Responder = new Responder(onSaveBadge, onSaveBadgeFailed);
			conn.call("GameService.save_badge", responder,user_id,badge_id,'berlin_2');
		}
		private function onSaveBadgeFailed(rs:Object) {
			is_win = false;
			current_level -= 1;
			user_last_level -= 1;
			game_to_result_transition();
			
		}
		private function onSaveBadge(rs:Object):void {
			PBE.log(this, "Saving badge status --> " + rs);
			PBE.log(this, "connecting to " + remote_url);
			game_to_result_transition();
		}
		private function onSaveScore(rs:Object):void {
			PBE.log(this, "Saving score status --> " + rs);
			
			//user_last_level = _current_level;
			PBE.log(this, "User last level :" + user_last_level);
		}
		private function save_score(user_id:String,score:int,level:int):void {
			var conn:NetConnection = new NetConnection();
			conn.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus, false, 0, true);
			conn.objectEncoding = ObjectEncoding.AMF3;
			conn.connect(remote_url);
			var responder:Responder = new Responder(onSaveScore, onStatus);
			conn.call("GameService.save_score", responder, user_id, 2, level, score);
		}
		private function init_config():void {
			var config:IEntity = PBE.lookupEntity("game_config");
			trace("config -->" + config);
			remote_url = config.getProperty(new PropertyReference("@config.remote_url"));
			PBE.log(this, "connecting to " + remote_url);
			var conn:NetConnection = new NetConnection();
			conn.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus, false, 0, true);
			conn.objectEncoding = ObjectEncoding.AMF3;
			conn.connect(remote_url);
			var responder:Responder = new Responder(onResult, onStatus);
			conn.call("GameService.getUserStats", responder,user_id,2);
			PBE.log(this, "Remote Call");
		}
		
		private function onNetStatus(e:NetStatusEvent):void 
		{
			trace(e.info.code);
		}
		
		private function onStatus(status:Object):void 
		{
			PBE.log(this, "error");
			for(var id:String in status) {
				var value:Object = status[id];

			  trace(id + " = " + value);
			}
		}
		
		private function onResult(result:Object):void 
		{
			PBE.log(this, result.level);
			try{
				user_last_level = result.level;
			}catch (e:Error) {
				user_last_level = 0;
			}
			if (user_last_level == 3) {
				current_level = 1;
			}else {
				current_level = user_last_level + 1;
			}
			trace('current_level : ' + current_level);
			initScreens();
			LevelManager.instance.loadLevel(8);
		}
		//-->
		private function initScreens():void 
		{
			
			landing = new mc_landing();
			landing.visible = false;
			landing.btn1.addEventListener(MouseEvent.CLICK, onHowto, false, 0, true);
			landing.btn2.addEventListener(MouseEvent.CLICK, on_play, false, 0, true);
			
			PBE.mainStage.addChild(landing);
			
			howto = new mc_howto();
			howto.visible = false;
			howto.btn.addEventListener(MouseEvent.CLICK, onHowtoPlayNow, false, 0, true);
			PBE.mainStage.addChild(howto);
			
			
			gameui = new mc_ui();
			gameui.y = 25;
			gameui.x = 5;
			gameui.visible = false;
			
			
			PBE.mainStage.addChild(gameui);
			
			PBE.mainStage.addEventListener("NEW_LEVEL", onNewLevel, false, 0, true);
			
			win = new mc_win();
			win.alpha = 0.0;
			win.visible = false;
			win.title2.visible = false;
			win.txt2.visible = false;
			win.btn2.visible = false;
			win.btn.addEventListener(MouseEvent.CLICK, onWinBtn, false, 0, true);
			win.btn2.addEventListener(MouseEvent.CLICK, onWinBtn, false, 0, true);
			PBE.mainStage.addChild(win);
			
			lose = new mc_lose();
			lose.alpha = 0.0;
			lose.visible = false;
			lose.btn.addEventListener(MouseEvent.CLICK, onLoseBtn, false, 0, true);
			PBE.mainStage.addChild(lose);
			
			piring = new mc_piring();
			reset_piring();
			PBE.mainStage.addChild(piring);
			
			lv1 = new mc_lv1go();
			lv1.x = 220;
			lv1.y = 220;
			lv1.alpha = 0;
			PBE.mainStage.addChild(lv1);
			
			lv2 = new mc_lv2go();
			lv2.x = 220;
			lv2.y = 220;
			lv2.alpha = 0;
			PBE.mainStage.addChild(lv2);
			
			lv3 = new mc_lv3go();
			lv3.x = 220;
			lv3.y = 220;
			lv3.alpha = 0;
			PBE.mainStage.addChild(lv3);
			//anim_level_go(lv1);
			
			
			//ini di comment ketika live
			//PBE.mainStage.addChild(new Stats());
			create_skip_button();
		}
		
		private function onLoseBtn(e:MouseEvent):void 
		{
			result_to_game_transition();
		}
		
		private function onWinBtn(e:MouseEvent):void 
		{
			result_to_game_transition();
		}
		private function create_skip_button():void {
			var btn:Sprite = new Sprite();
			btn.graphics.beginFill(0xcc0000);
			btn.graphics.drawRect(0, 0, 20, 20);
			btn.graphics.endFill();
			btn.x = 400;
			btn.y = 10;
			btn.buttonMode = true;
			btn.addEventListener(MouseEvent.CLICK, onSkip, false, 0, true);
			PBE.mainStage.addChild(btn);
		}
		
		private function onSkip(e:MouseEvent):void 
		{
			is_win = true;
			PBE.mainStage.dispatchEvent(new Event("SKIPPED"));
		}
		private function anim_level_go(mc:*):void {
			TweenLite.to(mc, 1, {alpha:1.0,onComplete:complete_anim_go,onCompleteParams:[mc]} );
		}
		private function complete_anim_go(param1:*):void {
			TweenLite.to(param1, 1, {alpha:0.0,delay:0.4} );
		}
		private function reset_piring():void {
			piring.x = 813;
			piring.y = -600;
			piring.visible = false;
		}
		private function onHowtoPlayNow(e:MouseEvent):void 
		{
			howto.visible = false;
			new_level_transition();
			play_game();
		}
		private function play_game():void {
			switch(current_level) {
				case 1:
					LevelManager.instance.loadLevel(1);
				break;
				case 2:
					LevelManager.instance.loadLevel(2);
				break;
				case 3:
					LevelManager.instance.loadLevel(3);
				break;
				default:
				//do nothing
				break;
			}
		}
		private function on_play(e:MouseEvent):void 
		{	
			new_level_transition();
			play_game();
			landing.visible = false;
		}
		
		private function onHowto(e:MouseEvent):void 
		{
			howto.visible = true;
			//landing.visible = false;
			howto_transation();
		}
		
		private function onLevelLoaded(e:LevelEvent):void 
		{
			
			//init_config();
			switch(LevelManager.instance.currentLevel) {
				case 1:
					level_1();
				break;
				case 2:
					level_2();
				break;
				case 3:
					level_3();
				break;
				case 5:
					landing.visible = false;
					gameui.visible = true;
				break;
				case 6:
					landing.visible = false;
					gameui.visible = true;
				break;
				case 7:
					landing.visible = false;
					gameui.visible = true;
				break;
				case 8:
					home();
				break;
				default:
					//home();
					init_config();
				break;
			}
		}
		private function home():void {
			PBE.log(this, "Home Screen");
			landing.visible = true;
		}
		private function level_1():void{
			landing.visible = false;
			gameui.visible = true;
		}
		private function level_2():void{
			landing.visible = false;
			gameui.visible = true;
		}
		private function level_3():void{
			landing.visible = false;
			gameui.visible = true;
		}
		/**
		 * setelah BGM di load.. baru kita play gamenya.
		 * @param	e
		 */
		private function onBGMLoaded(e:ResourceEvent):void 
		{
			
			
		}
		
	}
	
}