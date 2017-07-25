package
{
	// Flash Imports
	import br.com.stimuli.loading.BulkLoader;
	import br.com.stimuli.loading.BulkProgressEvent;
	import br.com.stimuli.loading.loadingtypes.ImageItem;
	import com.pblabs.engine.core.IPBObject;
	import com.pblabs.engine.core.LevelManager;
	import com.pblabs.engine.core.PBGroup;
	import com.pblabs.engine.entity.IEntity;
	import com.pblabs.engine.entity.IEntityComponent;
	import com.pblabs.engine.entity.PropertyReference;
	import com.pblabs.engine.resource.ResourceEvent;
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
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.events.NetStatusEvent;
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
	import flash.display.Sprite;
	import com.greensock.*;
	import com.greensock.easing.*;
	import mx.rpc.remoting.mxml.*;
	
	// PushButton Engine Imports
	import com.pblabs.engine.PBE;
	import com.pblabs.engine.debug.Logger;
	
	[SWF(width="798",height="564",backgroundColor="0x000000")]
	public class Main extends Sprite
	{
		private var swf:SWFResource;
		private var landing:mc_landing;
		private var howto:mc_howto;
		private var _ct1:ct1;
		private var _ct2:ct2;
		private var _ct3:ct3;
		private var _bg_blur:bg_blur;
		private var _bg_game:mc_bg_game;
		private var _lv1go:mc_lv1go;
		private var _lv2go:mc_lv2go;
		private var _lv3go:mc_lv3go;
		
		private var win:mc_win;
		private var lose:mc_lose;
		private var bg_wall:mc_bg_wall2;
		
		private var _current_level:int = 1;
		private var panel:time_panel;
		
		private var pause1:pause_level1;
		private var pause2:pause_level2;
		private var pause3:pause_level3;
		private var hint:mc_hint;
		
		private var badges:Array;
		private var badge_chance:Array = [0.3, 0.3, 0.1, 0.1, 0.1, 0.05, 0.05,0.05,0.05,0.05];
		public var user_last_level:int = 0; //artinya newbie. belum main sama sekali
		private var user_id:String = "1";
		private var remote_url:String;
		private var score:Number = 100;
		private var flashvars:Object;
		private var btncheat:btn_cheat;
		private var steps:int = 0;
		private var time_left:int = 0;
		public function Main():void
		{
			
			
			PBE.registerType(com.pblabs.rendering2D.SpriteRenderer);
			PBE.registerType(com.pblabs.rendering2D.SimpleSpatialComponent);
			PBE.registerType(com.pblabs.rendering2D.modifier.BlurModifier);
			PBE.registerType(com.pblabs.rendering2D.SpriteSheetRenderer);
			PBE.registerType(com.pblabs.rendering2D.spritesheet.SpriteSheetComponent);
			PBE.registerType(com.pblabs.rendering2D.spritesheet.CellCountDivider);
			PBE.registerType(com.pblabs.rendering2D.MovieClipRenderer);
			PBE.registerType(TimeTickerComponent);
			PBE.registerType(GameScreenComponent);
			PBE.registerType(PuzzlePickedComponent);
			PBE.registerType(PuzzleStatus);
			PBE.registerType(mc_landing);
			PBE.registerType(ConfigComponent);
			
			PBE.startup(this);
			flashvars = PBE.getFlashVars();
			try {
				user_id = flashvars.user_id;
			}catch(e:Error){
				user_id = "0";
			}
			//load resources bundle
			//PBE.resourceManager.onlyLoadEmbeddedResources = true;
			PBE.addResources(new Resources());
			
			
			var sv:SceneView = new SceneView();
			
			//sv.addChild(landing);
			PBE.initializeScene(sv);
			
			
			initResources();
			//initEverything();
		}
		private function loadConfig():void {
			var config:IEntity = PBE.lookupEntity("game_config");
			remote_url = config.getProperty(new PropertyReference("@config.remote_url"));
			PBE.log(this, "connecting to " + remote_url);
			var conn:NetConnection = new NetConnection();
			conn.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus, false, 0, true);
			conn.objectEncoding = ObjectEncoding.AMF3;
			conn.connect(remote_url);
			var responder:Responder = new Responder(onResult, onStatus);
			conn.call("GameService.getUserStats", responder,user_id,1);
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
			user_last_level = result.level;
			if (user_last_level == 3) {
				_current_level = 1;
			}else {
				_current_level = user_last_level + 1;
			}
			home_screen();
		}
		
		private function initResources():void {
			/*var bulk:BulkLoader = new BulkLoader("main");
			bulk.allowsAutoIDFromFileName = true;
			bulk.add("../assets/bg_game.jpg");
			bulk.add("../assets/gravity1.jpg");
			
			
			bulk.addEventListener(BulkProgressEvent.COMPLETE, onResourceLoaded, false, 0, true);
			bulk.addEventListener(BulkProgressEvent.PROGRESS, _onProgressHandler, false, 0, true);
			bulk.start(3);
			*/
			
			//swf  = new SWFResource();
			//swf.addEventListener(ResourceEvent.LOADED_EVENT, onSWFResourceLoaded, false, 0, true);
			//swf.load("../assets/landing.swf");
		
			initEverything();
		}
		private function onSWFResourceLoaded(evt:ResourceEvent):void {
			PBE.log(this, "resource is loaded !!");
			var mc:MovieClip = swf.getExportedAsset("mc_landing") as MovieClip;
			
			//addChild(mc);
			initEverything();
		}
		private function onResourceLoaded(evt:BulkProgressEvent):void {
			PBE.log(this, "resource is loaded !!");
			
			initEverything();
		}
		private function _onProgressHandler(evt : BulkProgressEvent) : void{
			trace("Loaded" , evt.bytesLoaded," of ",  evt.bytesTotal);
		}
		private function initEverything():void {
			PBE.log(this, "Starting up");
			PBE.mainStage.addEventListener("WIN", onWin, false, 0, true);
			PBE.mainStage.addEventListener("LOSE", onLose, false, 0, true);
			PBE.mainStage.addEventListener("GAME_PAUSED", onGamePaused, false, 0, true);
			//load level
			LevelManager.instance.addEventListener(LevelEvent.LEVEL_LOADED_EVENT, onLevelLoaded, false, 0, true);
			//LevelManager.instance.addEventListener(LevelEvent.LEVEL_UNLOADED_EVENT, onLevelUnloaded, false, 0, true);
			LevelManager.instance.load("../assets/LevelDescriptions.xml",4);
			
			//prepare for the screens
			initScreens();
		}
		
		private function onGamePaused(e:Event):void 
		{
			if (_current_level == 1) {
				pause1.visible = true;
			}else if (_current_level == 2) {
				pause2.visible = true;
			}else if (_current_level == 3) {
				pause3.visible = true;
			}else {
				//do nothing
			}
		}
		
		private function onLose(e:Event):void 
		{
			for each(var entity:IPBObject in PBE.nameManager.objectList) {
			
					entity.destroy();
				
			}
			var sv:SceneView = new SceneView();
			PBE.initializeScene(sv);
			hint.visible = false;
			panel.visible = false;
			bg_wall.visible = true;
			pause1.visible = false;
			pause2.visible = false;
			pause3.visible = false;
			lose.visible = true;
			lose.x = 2000;
			
			TweenLite.to(lose, 0.5, {x:120, ease:Quart.easeOut});
		}
		private function initScreens():void {
			landing = new mc_landing();
			landing.visible = false;
			landing.y = 50;
			landing.btn1.addEventListener(MouseEvent.CLICK, onHowto, false, 0, true);
			landing.btn2.addEventListener(MouseEvent.CLICK, onPlayGame, false, 0, true);
			PBE.mainStage.addChild(landing);
			
			howto = new mc_howto();
			howto.y = 50;
			howto.visible = false;
			howto.btn1.addEventListener(MouseEvent.CLICK, onPlayGame, false, 0, true);
			PBE.mainStage.addChild(howto);
			
			_ct1 = new ct1();
			_ct2 = new ct2();
			_ct3 = new ct3();
			_bg_blur = new bg_blur();
			
			_ct1.visible = false;
			_ct2.visible = false;
			_ct3.visible = false;
			
			_ct1.x = PBE.mainStage.stageWidth/2/2;
			_ct2.x = PBE.mainStage.stageWidth/2/2;
			_ct3.x = PBE.mainStage.stageWidth/2/2;
			_ct1.y = 180;
			_ct2.y = 180;
			_ct3.y = 180;
			_bg_blur.visible = false;
			
			_lv1go = new mc_lv1go();
			_lv2go = new mc_lv2go();
			_lv3go = new mc_lv3go();
			_bg_game = new mc_bg_game();
			
			_lv1go.visible = false;
			_lv2go.visible = false;
			_lv3go.visible = false;
			_lv1go.x = PBE.mainStage.stageWidth/2/2;
			_lv2go.x = PBE.mainStage.stageWidth/2/2;
			_lv3go.x = PBE.mainStage.stageWidth/2/2;
			_lv1go.y = 180;
			_lv2go.y = 180;
			_lv3go.y = 180;
			_bg_game.visible = false;
			
			win = new mc_win();
			win.x = 120;
			win.y = 60;
			win.visible = false;
			win.btn_continue.addEventListener(MouseEvent.CLICK, onNextLevel, false, 0, true);
			win.btn_play_again.addEventListener(MouseEvent.CLICK, onPlayAgain, false, 0, true);
			win.btn.addEventListener(MouseEvent.CLICK, onNextLevel, false, 0, true);
			
			lose = new mc_lose();
			lose.x = 120;
			lose.y = 60;
			lose.visible = false;
			lose.btn.addEventListener(MouseEvent.CLICK, onTryAgain, false, 0, true);
			
			bg_wall = new mc_bg_wall2();
			bg_wall.visible = false;
			
			
			//game panel
			panel = new time_panel();
			panel.x = 80;
			panel.y = 480;
			panel.visible = false;
			panel.btn.addEventListener(MouseEvent.CLICK, onPaused, false, 0, true);
			PBE.mainStage.addChild(panel);
			
			//pause screen
			pause1 = new pause_level1();
			pause2 = new pause_level2();
			pause3 = new pause_level3();
			
			pause1.x = 6;
			pause1.y = 6;
			pause2.x = 6;
			pause2.y = 6;
			pause3.x = 6;
			pause3.y = 6;
			
			pause1.visible = false;
			pause2.visible = false;
			pause3.visible = false;
			
			pause1.btn.addEventListener(MouseEvent.CLICK, onResume, false, 0, true);
			pause2.btn.addEventListener(MouseEvent.CLICK, onResume, false, 0, true);
			pause3.btn.addEventListener(MouseEvent.CLICK, onResume, false, 0, true);
			
			
			
			//hint button
			hint = new mc_hint();
			hint.x = 650;
			hint.y = 50;
			hint.visible = false;
			hint.txt.visible = false;
			hint.btn.addEventListener(MouseEvent.CLICK, onHint, false, 0, true);
			PBE.mainStage.addChild(hint);
			
			
			//button cheat auto-win
			btncheat = new btn_cheat();
			btncheat.visible = false;
			btncheat.addEventListener(MouseEvent.CLICK, onBtnCheat, false, 0, true);
			PBE.mainStage.addChild(btncheat);
			
			
			//badges
			badges = [null,new badge1(),new badge2(),new badge3(),new badge4(),new badge5(),new badge6(),new badge7(),new badge8(),new badge9(),new badge10()];
			
			
			//-->
			PBE.mainStage.addChild(_bg_blur);
			PBE.mainStage.addChild(_bg_game);
			PBE.mainStage.addChild(bg_wall);
			PBE.mainStage.addChild(_ct1);
			PBE.mainStage.addChild(_ct2);
			PBE.mainStage.addChild(_ct3);
			
			PBE.mainStage.addChild(_lv1go);
			PBE.mainStage.addChild(_lv2go);
			PBE.mainStage.addChild(_lv3go);
			
			PBE.mainStage.addChild(pause1);
			PBE.mainStage.addChild(pause2);
			PBE.mainStage.addChild(pause3);
			PBE.mainStage.addChild(win);
			
			for (var i:int = 1; i < badges.length; i++) {
				badges[i].x = 300;
				badges[i].y = 50;
				badges[i].visible = false;
				PBE.mainStage.addChild(badges[i]);
			}
			PBE.mainStage.addChild(lose);
		}
		
		private function onPlayAgain(e:MouseEvent):void 
		{
			for (var i:int = 1; i < badges.length; i++) {
				badges[i].visible = false;
			}
			_current_level = 1;
			if (_current_level > 3) {
				_current_level = 0;
			}else {
				TweenLite.to(win, 0.5, {x:-2000, y:howto.y, ease:Quart.easeOut,onComplete:onNextLevelTransation} );
				//count_down();
			}
		}
		
		private function onBtnCheat(e:MouseEvent):void 
		{
			PBE.mainStage.dispatchEvent(new Event("WIN"));
		}
		
		private function onHint(e:MouseEvent):void 
		{
			if(hint.txt.text!="0"){
				PBE.mainStage.dispatchEvent(new Event("HINT"));
			}
		}
		
		private function onResume(e:MouseEvent):void 
		{
			pause1.visible = false;
			pause2.visible = false;
			pause3.visible = false;
			var status:IEntity = PBE.lookupEntity('status');
			status.setProperty(new PropertyReference("@puzzle_status.isPaused"),false);
			PBE.mainStage.dispatchEvent(new Event("GAME_RESUMED"));
		}
		
		private function onTryAgain(e:MouseEvent):void 
		{
			bg_wall.visible = false;
			lose.visible = false;
			count_down();
		}
		private function onPaused(e:MouseEvent):void {
			var status:IEntity = PBE.lookupEntity('status');
			status.setProperty(new PropertyReference("@puzzle_status.isPaused"),true);
			PBE.mainStage.dispatchEvent(new Event("GAME_PAUSED"));
		}
		private function onNextLevel(e:MouseEvent):void 
		{
			for (var i:int = 1; i < badges.length; i++) {
				badges[i].visible = false;
			}
			_current_level += 1;
			if (_current_level > 3) {
				_current_level = 0;
			}else {
				TweenLite.to(win, 0.5, {x:-2000, y:howto.y, ease:Quart.easeOut,onComplete:onNextLevelTransation} );
				//count_down();
			}
		}
		private function onNextLevelTransation():void {
			bg_wall.visible = false;
			if(_current_level!=0){
				count_down();
			}else {
				PBE.levelManager.loadLevel(4, true);
			}
		}
		private function onLevelLoaded(evt:LevelEvent):void {
			if (LevelManager.instance.currentLevel == 1) {
				onLevel1(evt);
			}else if(LevelManager.instance.currentLevel == 2){
				onLevel2(evt);
			}else if(LevelManager.instance.currentLevel == 3){
				onLevel3(evt);
			}else {
				//trace("yey");
				onHomeScreen(evt);
			}
		}
		private function onHomeScreen(evt:LevelEvent):void {
			loadConfig();
			//PBE.templateManager.instantiateEntity('landing');
		}
		private function home_screen():void{
			PBE.log(this, "Home Screen");
			landing.visible = true;
			landing.x = 2000;
			TweenLite.to(landing, 0.5, {x:0, y:landing.y, ease:Quart.easeOut});
		}
		
		private function onPlayGame(e:MouseEvent):void 
		{
			bg_wall.visible = false;
			if (landing.visible == false) {
				TweenLite.to(howto, 0.5, {x:-2000, y:howto.y, ease:Quart.easeOut,onComplete:onPlayGameTransition});
			}else {
				TweenLite.to(landing, 0.5, {x:-2000, y:landing.y, ease:Quart.easeOut});
				PBE.log(this, "Langsung main game :)");
			}
			count_down();
			//e.target.parent.visible = false;
			//LevelManager.instance.loadLevel(1);
		}
		private function count_down():void {
			_ct1.visible = true;
			_ct1.x = 2000;
			
			_ct2.visible = true;
			_ct2.x = 2000;
			
			_ct3.visible = true;
			_ct3.x = 2000;
			
			switch(_current_level) {
				case 1:
					_lv1go.visible = true;
					_lv1go.x = 2000;
					TweenLite.to(_lv1go, 0.5, { x:PBE.mainStage.stageWidth/2/2,ease:Quart.easeOut,delay:3,onComplete:countdown_finish} );
				break;
					
				case 2:
					_lv2go.visible = true;
					_lv2go.x = 2000;
					TweenLite.to(_lv2go, 0.5, { x:PBE.mainStage.stageWidth/2/2,ease:Quart.easeOut,delay:3,onComplete:countdown_finish} );
				break;
				
				case 3:
					_lv3go.visible = true;
					_lv3go.x = 2000;
					TweenLite.to(_lv3go, 0.5, { x:PBE.mainStage.stageWidth/2/2,ease:Quart.easeOut,delay:3,onComplete:countdown_finish} );
				break;
				default:
					//do nothing
				break;
			}
			
			_bg_blur.visible = true;
			
			TweenLite.to(_ct1, 0.5, { x:PBE.mainStage.stageWidth / 2 / 2, ease:Quart.easeOut, onComplete:function() {
					TweenLite.to(_ct1, 0.5, { x:-400, ease:Quart.easeOut, delay:0.5 });
				}} );
			
			TweenLite.to(_ct2, 0.5, { x:PBE.mainStage.stageWidth / 2 / 2, ease:Quart.easeOut,delay:1, onComplete:function() {
					TweenLite.to(_ct2, 0.5, { x:-400, ease:Quart.easeOut, delay:0.5 });
				}} );
				
			TweenLite.to(_ct3, 0.5, { x:PBE.mainStage.stageWidth / 2 / 2, ease:Quart.easeOut,delay:2, onComplete:function() {
					TweenLite.to(_ct3, 0.5, { x:-400, ease:Quart.easeOut, delay:0.5 });
				}} );
				
			
				
			//TweenLite.to(_ct1, 0.5, { x:-2000, ease:Quart.easeIn,delay:3} );
			
			/*
			TweenLite.to(_ct2, 0.5, { x:PBE.mainStage.stageWidth / 2 / 2, ease:Quart.easeOut, delay:1 } );
			//TweenLite.to(_ct2, 0.5, { x: -2000, ease:Quart.easeOut, delay:3 } );
			
			TweenLite.to(_ct3, 0.5, { x:PBE.mainStage.stageWidth / 2 / 2, ease:Quart.easeOut, delay:2 } );
			//TweenLite.to(_ct3, 0.5, { x:-2000, ease:Quart.easeOut,delay:3} );
			*/
		}
		private function countdown_finish():void {
			switch(_current_level) {
				case 1:
					TweenLite.to(_lv1go, 0.5, { x:-400,ease:Quart.easeOut,delay:0.5,onComplete:play_game} );
				break;
					
				case 2:
					
					TweenLite.to(_lv2go, 0.5, { x:-400,ease:Quart.easeOut,delay:0.5,onComplete:play_game} );
				break;
				
				case 3:
					TweenLite.to(_lv3go, 0.5, { x:-400,ease:Quart.easeOut,delay:0.5,onComplete:play_game} );
				break;
				default:
					//do nothing
				break;
			}
		}
		private function play_game():void {
			_bg_game.visible = false;
			_bg_blur.visible = false;
			_ct1.visible = false;
			_ct2.visible = false;
			_ct3.visible = false;
			_lv1go.visible = false;
			_lv2go.visible = false;
			_lv3go.visible = false;
			
			PBE.levelManager.loadLevel(_current_level,true);
		}
		private function onPlayGameTransition():void {
			howto.visible = false;
		}
		private function onHowto(e:MouseEvent):void 
		{
			PBE.log(this, "Howto Screen");
			howto.x = 2000;
			howto.visible = true;
			//landing.visible = false;
			TweenLite.to(landing, 0.5, {x:-2000, y:landing.y, ease:Quart.easeOut});
			TweenLite.to(howto, 0.5, {x:0, y:howto.y, ease:Quart.easeOut,onComplete:onHowtoComplete});
		}
		private function onHowtoComplete():void {
			landing.visible = false;
		}
		private function onLevelUnloaded(evt:LevelEvent):void {
			PBE.log(this, LevelManager.instance.currentLevel + " Unloaded");
		}
		private function onWin(e:Event):void 
		{
			PBE.log(this, "WINNING");
			var status:IEntity = PBE.lookupEntity("status");
			
			try{
				time_left = status.getProperty(new PropertyReference("@puzzle_status.time_left"));
				steps = status.getProperty(new PropertyReference("@puzzle_status.steps"));
			}catch (e:Error) {
				//pastiin uda di reset (parno mode)
				time_left = 0;
				steps = 0;
				//-->
			}
			//LevelManager.instance.unloadCurrentLevel();
			try{
				for each(var entity:IPBObject in PBE.nameManager.objectList) {
					//if (entity.name != "SceneDB" && entity.name!="RootGroup" && entity != null) {
					//	trace(entity.name + " Destroyed");
						entity.destroy();
					//}
				}
				//LevelManager.instance.loadLevel(2, true);
				var sv:SceneView = new SceneView();
				PBE.initializeScene(sv);
			}catch (e:Error) {
				trace("ERROR --> "+e.message);
			}
			panel.visible = false;
			bg_wall.visible = true;
			win.txt1.visible = false;
			win.txt2.visible = false;
			win.txt3.visible = false;
			win.visible = true;
			win.title.visible = false;
			win.title2.visible = false;
			win.x = 2000;
			win.btn_continue.visible = false;
			win.btn.visible = false;
			win.btn_play_again.visible = false;
			
			pause1.visible = false;
			pause2.visible = false;
			pause3.visible = false;
			
			
			TweenLite.to(win, 0.5, {x:120, ease:Quart.easeOut,onComplete:win_complete});
			
		}
		private function win_complete():void {
			var acquired:Boolean = false; //acquired a badge ?
			PBE.log(this, user_last_level.toString() + " vs " + _current_level);
			if (user_last_level < _current_level) {
					user_last_level = _current_level;
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
					PBE.log(this, "get new badge nih !");
					acquired = true;
					badges[winner + 1].visible = true;
					PBE.log(this, "connecting to " + remote_url);
					var conn:NetConnection = new NetConnection();
					conn.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus, false, 0, true);
					conn.objectEncoding = ObjectEncoding.AMF3;
					conn.connect(remote_url);
					var responder:Responder = new Responder(onSaveBadge, onStatus);
					conn.call("GameService.save_badge", responder,user_id,(winner+1),'berlin1');
			}else{
				PBE.log(this, "connecting to " + remote_url);
				var conn:NetConnection = new NetConnection();
				conn.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus, false, 0, true);
				conn.objectEncoding = ObjectEncoding.AMF3;
				conn.connect(remote_url);
				var responder:Responder = new Responder(onSaveScore, onStatus);
				score = this.getScore();
				conn.call("GameService.save_score", responder, user_id, 1, _current_level, score);
			}
			if (acquired) {
				PBE.log(this, "LOH !");
				win.title.visible = true;
				win.title2.visible = false;
				
				//win.title.txt.styleSheet.setStyle("fontWeight", "bold");
				win.txt1.visible = true;
				win.btn.visible = true;
				
			}else if (!acquired && user_last_level > _current_level) {
				win.title.visible = false;
				win.title2.visible = true;
				
				win.txt2.visible = true;
				win.btn_continue.visible = true;
			}else{
				win.title.visible = false;
				win.title2.visible = true;
				win.txt1.visible = true;
				win.btn.visible = true;
			}
			if (_current_level == 3) {
				win.txt1.visible = false;
				win.btn.visible = false;
				
				win.txt2.visible = false;
				win.btn_continue.visible = false;
				win.txt3.visible = true;
				win.btn_play_again.visible = true;
			}
		}
		private function getScore():int {
			//calculate the bonuse first
			
			//PBE.log(this, "Score = " + time_left + "x10 + rate dari " + steps);
			var bonus:int = 0;
			switch(_current_level){
				case 2:
					//level 1
					if (steps <= 10) {
						bonus = 100;
					}else if (steps > 10 && steps <= 20) {
						bonus = 80;
					}else if (steps > 20 && steps <= 30) {
						bonus = 50;
					}else if (steps > 30 && steps <= 40) {
						bonus = 20;
					}else {
						bonus = 0;
					}
				break;
				case 3:
					//level 1
					if (steps <= 20) {
						bonus = 100;
					}else if (steps > 20 && steps <= 40) {
						bonus = 80;
					}else if (steps > 40 && steps <= 60) {
						bonus = 50;
					}else if (steps > 60 && steps <= 80) {
						bonus = 20;
					}else {
						bonus = 0;
					}
				break;
				default:
					//level 1
					if (steps <= 5) {
						bonus = 100;
					}else if (steps > 5 && steps <= 10) {
						bonus = 80;
					}else if (steps > 10 && steps <= 15) {
						bonus = 50;
					}else if (steps > 15 && steps <= 20) {
						bonus = 20;
					}else {
						bonus = 0;
					}
				break;
			}
			var score:int = time_left * 10 + bonus;
			PBE.log(this, "Player score for level " + _current_level + " : " + score.toString());
			return score;
		}
		private function onSaveBadge(rs:Object):void {
			PBE.log(this, "Saving badge status --> " + rs);
			PBE.log(this, "connecting to " + remote_url);
				var conn:NetConnection = new NetConnection();
				conn.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus, false, 0, true);
				conn.objectEncoding = ObjectEncoding.AMF3;
				conn.connect(remote_url);
				var responder:Responder = new Responder(onSaveScore, onStatus);
				score = this.getScore();
				conn.call("GameService.save_score", responder, user_id, 1, _current_level, score);
		}
		private function onSaveScore(rs:Object):void {
			PBE.log(this, "Saving score status --> " + rs);
			
			//user_last_level = _current_level;
			PBE.log(this, "User last level :" + user_last_level);
		}
		private function shuffle(arr:Array):Array {
			var arr2:Array = [];
			while (arr.length > 0) {
				arr2.push(arr.splice(Math.round(Math.random() * (arr.length - 1)), 1)[0]);
			}
			return arr2;
		}
		private function onLevel1(evt:LevelEvent):void {
			//var statusEntity:IEntity = PBE.lookupEntity("status");
			//statusEntity.getProperty(new PropertyReference("@timer.panel"), panel);
			var pieces:Array = [PBE.lookupEntity("puzzle1"), PBE.lookupEntity("puzzle2"),
								PBE.lookupEntity("puzzle3"), PBE.lookupEntity("puzzle4"),
								PBE.lookupEntity("puzzle5"), PBE.lookupEntity("puzzle6"),
								PBE.lookupEntity("puzzle7"), PBE.lookupEntity("puzzle8"),
								PBE.lookupEntity("puzzle9"), PBE.lookupEntity("puzzle10")];
				
			//randomize the puzzle
			var puzzle:Array = shuffle(pieces);
			//var puzzle:Array = pieces;
			var status:IEntity = PBE.lookupEntity("status");
			panel.panel.timebar.gotoAndStop(1);
			status.setProperty(new PropertyReference("@timer.panel"), panel);
			var stack:Array = [];
			
		//	bg.setProperty(new PropertyReference("@render.alpha"),0.5);
			
			var _middle_:Number = 784 / 2;
			var i:int = 0;
			var j:int = -151;
			var positions:Object = { puzzle1:null, puzzle2:null, puzzle3:null, puzzle4:null, puzzle5:null,
									puzzle6:null, puzzle7:null, puzzle8:null, puzzle9:null, puzzle10:null };
									
			for(var idx:int = 0;idx < puzzle.length; idx++ ) {
				var o:IEntity = puzzle[idx];
				var position:Point = new Point();
				//trace(o.name);
				position = o.getProperty(new PropertyReference("@Spatial.position")) as Point;
				position.x = -(_middle_) + (i * 780 / 5) + (780 / 5 / 2);
				position.y = j;
				positions[o.name] = position;
				stack.push(o.name);
				var outline:BorderModifier = new BorderModifier();
				//o.setProperty(new PropertyReference("@render.modifiers"), [outline]);
				o.setProperty(new PropertyReference("@Spatial.position"), position);
				i++;
				if (i > 0 && i % 5 == 0) {
					i = 0;
					j += (350 / 2);
				}
			}
			status.setProperty(new PropertyReference('@puzzle_status.positions'), positions);
			status.setProperty(new PropertyReference('@puzzle_status.stack'), stack);
			hint.txt.text = status.getProperty(new PropertyReference("@puzzle_status.n_hint"));
			status.setProperty(new PropertyReference("@timer.hint"), hint);
			hint.txt.visible = false;
			hint.visible = true;
			panel.visible = true;
			//nyalakan timer
			PBE.mainStage.dispatchEvent(new Event("GAME_STARTED"));
		}
		private function onLevel2(evt:LevelEvent):void {
		
			var pieces:Array = [PBE.lookupEntity("puzzle1"), PBE.lookupEntity("puzzle2"),
								PBE.lookupEntity("puzzle3"), PBE.lookupEntity("puzzle4"),
								PBE.lookupEntity("puzzle5"), PBE.lookupEntity("puzzle6"),
								PBE.lookupEntity("puzzle7"), PBE.lookupEntity("puzzle8"),
								PBE.lookupEntity("puzzle9"), PBE.lookupEntity("puzzle10"),
								PBE.lookupEntity("puzzle11"), PBE.lookupEntity("puzzle12"),
								PBE.lookupEntity("puzzle13"), PBE.lookupEntity("puzzle14"),
								PBE.lookupEntity("puzzle15"), PBE.lookupEntity("puzzle16"),
								PBE.lookupEntity("puzzle17"), PBE.lookupEntity("puzzle18"),
								PBE.lookupEntity("puzzle19"), PBE.lookupEntity("puzzle20"),
								PBE.lookupEntity("puzzle21")];
				
			//randomize the puzzle
			var puzzle:Array = shuffle(pieces);
			//var puzzle:Array = pieces;
			var status:IEntity = PBE.lookupEntity("status");
			panel.panel.timebar.gotoAndStop(1);
			status.setProperty(new PropertyReference("@timer.panel"), panel);
			
			
			var stack:Array = [];
			
		
			
			var _middle_:Number = 784 / 2;
			var i:int = 0;
			var j:int = -181;
			var positions:Object = { puzzle1:null, puzzle2:null, puzzle3:null, puzzle4:null, puzzle5:null,
									puzzle6:null, puzzle7:null, puzzle8:null, puzzle9:null, puzzle10:null,
									puzzle11:null, puzzle12:null, puzzle13:null, puzzle14:null, puzzle15:null,
									puzzle16:null,puzzle17:null,puzzle18:null,puzzle19:null,puzzle20:null,puzzle21:null };
									
			for(var idx:int = 0;idx < puzzle.length; idx++ ) {
				var o:IEntity = puzzle[idx];
				var position:Point = new Point();
				//trace(o.name);
				position = o.getProperty(new PropertyReference("@Spatial.position")) as Point;
				position.x = -(_middle_) + (i * 780 / 7) + (780 / 7 / 2);
				position.y = j;
				positions[o.name] = position;
				stack.push(o.name);
				var outline:BorderModifier = new BorderModifier();
				//o.setProperty(new PropertyReference("@render.modifiers"), [outline]);
				o.setProperty(new PropertyReference("@Spatial.position"), position);
				i++;
				if (i > 0 && i % 7 == 0) {
					i = 0;
					j += (350 / 3);
				}
			}
			status.setProperty(new PropertyReference('@puzzle_status.positions'), positions);
			status.setProperty(new PropertyReference('@puzzle_status.stack'), stack);
			hint.txt.text = status.getProperty(new PropertyReference("@puzzle_status.n_hint"));
			status.setProperty(new PropertyReference("@timer.hint"), hint);
			hint.txt.visible = false;
			hint.visible = true;
			panel.visible = true;
			//nyalakan timer
			PBE.mainStage.dispatchEvent(new Event("GAME_STARTED"));
		}
		private function onLevel3(evt:LevelEvent):void {
		
			var pieces:Array = [PBE.lookupEntity("puzzle1"), PBE.lookupEntity("puzzle2"),
								PBE.lookupEntity("puzzle3"), PBE.lookupEntity("puzzle4"),
								PBE.lookupEntity("puzzle5"), PBE.lookupEntity("puzzle6"),
								PBE.lookupEntity("puzzle7"), PBE.lookupEntity("puzzle8"),
								PBE.lookupEntity("puzzle9"), PBE.lookupEntity("puzzle10"),
								PBE.lookupEntity("puzzle11"), PBE.lookupEntity("puzzle12"),
								PBE.lookupEntity("puzzle13"), PBE.lookupEntity("puzzle14"),
								PBE.lookupEntity("puzzle15"), PBE.lookupEntity("puzzle16"),
								PBE.lookupEntity("puzzle17"), PBE.lookupEntity("puzzle18"),
								PBE.lookupEntity("puzzle19"), PBE.lookupEntity("puzzle20"),
								PBE.lookupEntity("puzzle21"), PBE.lookupEntity("puzzle22"),
								PBE.lookupEntity("puzzle23"), PBE.lookupEntity("puzzle24"),
								PBE.lookupEntity("puzzle25"), PBE.lookupEntity("puzzle26"),
								PBE.lookupEntity("puzzle27"), PBE.lookupEntity("puzzle28"),
								PBE.lookupEntity("puzzle29"), PBE.lookupEntity("puzzle30"),
								PBE.lookupEntity("puzzle31"), PBE.lookupEntity("puzzle32"),
								PBE.lookupEntity("puzzle33"), PBE.lookupEntity("puzzle34"),
								PBE.lookupEntity("puzzle35"), PBE.lookupEntity("puzzle36"),
								PBE.lookupEntity("puzzle37"), PBE.lookupEntity("puzzle38"),
								PBE.lookupEntity("puzzle39"), PBE.lookupEntity("puzzle40")];
				
			//randomize the puzzle
			var puzzle:Array = shuffle(pieces);
			//var puzzle:Array = pieces;
			var status:IEntity = PBE.lookupEntity("status");
			panel.panel.timebar.gotoAndStop(1);
			status.setProperty(new PropertyReference("@timer.panel"), panel);
			
			
			var stack:Array = [];
			
		
			
			var _middle_:Number = 784 / 2;
			var i:int = 0;
			var j:int = -200;
			var positions:Object = {puzzle1:null, puzzle2:null, puzzle3:null, puzzle4:null, puzzle5:null,
									puzzle6:null, puzzle7:null, puzzle8:null, puzzle9:null, puzzle10:null,
									puzzle11:null, puzzle12:null, puzzle13:null, puzzle14:null, puzzle15:null,
									puzzle16:null, puzzle17:null, puzzle18:null, puzzle19:null, puzzle20:null, 
									puzzle21:null,puzzle22:null, puzzle23:null, puzzle24:null, puzzle25:null,
									puzzle26:null, puzzle27:null, puzzle28:null, puzzle29:null, puzzle30:null,
									puzzle31:null, puzzle32:null, puzzle33:null, puzzle34:null, puzzle35:null,
									puzzle36:null,puzzle37:null,puzzle38:null,puzzle39:null,puzzle40:null};
									
			for(var idx:int = 0;idx < puzzle.length; idx++ ) {
				var o:IEntity = puzzle[idx];
				var position:Point = new Point();
				//trace(o.name);
				position = o.getProperty(new PropertyReference("@Spatial.position")) as Point;
				position.x = -(_middle_) + (i * 780 / 10) + (780 / 10 / 2);
				position.y = j;
				positions[o.name] = position;
				stack.push(o.name);
				var outline:BorderModifier = new BorderModifier();
				//o.setProperty(new PropertyReference("@render.modifiers"), [outline]);
				o.setProperty(new PropertyReference("@Spatial.position"), position);
				i++;
				if (i > 0 && i % 10 == 0) {
					i = 0;
					j += (350 / 4);
				}
			}
			status.setProperty(new PropertyReference('@puzzle_status.positions'), positions);
			status.setProperty(new PropertyReference('@puzzle_status.stack'), stack);
			hint.txt.text = status.getProperty(new PropertyReference("@puzzle_status.n_hint"));
			status.setProperty(new PropertyReference("@timer.hint"), hint);
			hint.txt.visible = false;
			hint.visible = true;
			panel.visible = true;
			//nyalakan timer
			PBE.mainStage.dispatchEvent(new Event("GAME_STARTED"));
		}
		
		private function onLevel1End(evt:LevelEvent):void {
			PBE.log(this, "Level 1 completed !");
		}
	}
}