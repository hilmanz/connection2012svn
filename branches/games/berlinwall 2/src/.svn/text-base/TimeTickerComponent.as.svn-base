package  
{
	import com.pblabs.engine.components.TickedComponent;
	import com.pblabs.engine.entity.IEntity;
	import com.pblabs.engine.entity.PropertyReference;
	import com.pblabs.engine.PBE;
	import com.pblabs.rendering2D.modifier.animating.PulsatingGlowModifier;
	import com.pblabs.rendering2D.modifier.BlurModifier;
	import com.pblabs.rendering2D.modifier.BorderModifier;
	import com.pblabs.rendering2D.modifier.ColorizeModifier;
	import com.pblabs.rendering2D.modifier.GlowModifier;
	import com.pblabs.rendering2D.modifier.Modifier;
	import com.pblabs.rendering2D.modifier.SizeModifier;
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.utils.Timer;
	/**
	 * ...
	 * @author Kanadigital
	 */
	public class TimeTickerComponent  extends TickedComponent
	{
		private var GAME_TIME:Number = 0;
		private var total_time:Number = 0;
		private var timer:Timer
		public var panel:time_panel;
		private var current_frame:int = 0;
		public var hint:mc_hint;
		private var n_hint:int = 0;
		private var target1:IEntity;
		private var target2:IEntity;
		private var n_counter:int = 0;
		//private var timeutil:TimeUtil;
		public function TimeTickerComponent() 
		{
			super();
		}
		public function set game_time(n:Number):void {
			this.GAME_TIME = 0;
			this.total_time = n;
		}
		protected override function onAdd():void {
			
			PBE.log(this, "started");
			super.onAdd();
			//panel = PBE.mainStage.getChildByName("panel") as time_panel;
			
			
			//trace(panel.panel);
			timer = new Timer(1000);
			timer.addEventListener(TimerEvent.TIMER, onTimer, false, 0, true);
			PBE.mainStage.addEventListener("GAME_STARTED", onGameStarted, false, 0, true);
			PBE.mainStage.addEventListener("GAME_STOPED", onGameStoped, false, 0, true);
			PBE.mainStage.addEventListener("GAME_PAUSED", onGamePaused, false, 0, true);
			PBE.mainStage.addEventListener("GAME_RESUMED", onGameResumed, false, 0, true);
			PBE.mainStage.addEventListener("HINT", onHint, false, 0, true);
			PBE.mainStage.addEventListener("PIECE_RELEASED", onPieceReleased, false, 0, true);
		}
		
		private function onPieceReleased(e:Event):void 
		{
			try {
					trace("reset nih !");
					var modifiers1:Array = target1.getProperty(new PropertyReference("@render.modifiers"));
					//modifiers1 = [new GlowModifier(0xff000000)];
					modifiers1 = [];
					target1.setProperty(new PropertyReference("@render.modifiers"), modifiers1);
					var modifiers2:Array = target2.getProperty(new PropertyReference("@render.modifiers"));
					//modifiers2.push(new BlurModifier());
					//modifiers2 = [new BorderModifier(0xff000000)];
					modifiers2 = [];
					target2.setProperty(new PropertyReference("@render.modifiers"), modifiers2);
				
				}catch (e:Error) {
					
				}
		}
		
		private function onHint(e:Event):void 
		{
			//try {
				var status:IEntity = PBE.lookupEntity("status");
				var n_status:int = status.getProperty(new PropertyReference("@puzzle_status.n_hint"));
				var stack:Array = status.getProperty(new PropertyReference("@puzzle_status.stack"));
				n_status -= 1;
				if (n_status < 0) {
					n_status = 0;
				}else {
					open_hint(stack);
				}
				status.setProperty(new PropertyReference("@puzzle_status.n_hint"),n_status);
			//}catch (e:Error) {
			//		trace(e.message);
			//}
		}
		
		private function open_hint(stack:Array):void 
		{
			var i:int = 0;
			var limit:int = 100;
			var retry:int = 0;
			while (true) {
				i = Math.round(Math.random() * stack.length);
				if (stack[i] != "puzzle" + (i + 1)) {
					var n_to:int = (int(stack[i].substring(6, 8)))-1;
					var n_from:int = i;
					var targetName:String = stack[n_to];
					break;
					//PBE.log(this, stack[i] + "" + (i + 1) + " can move from " + i + " to " + stack[i].subString(6, 1));
				}
				retry++;
				if (retry == limit) {
					break;
				}
			}
			try{
				target1 = PBE.lookupEntity(stack[i]);
				target2 = PBE.lookupEntity(targetName);
				var modifiers1:Array = target1.getProperty(new PropertyReference("@render.modifiers"));
				modifiers1 = [new ColorizeModifier([0.5, 0.5, 0.5, 0, 0], [0.5, 0.5, 0.5, 0, 0], [0.5, 0, 0, 0, 0], [0, 0, 0, 1, 0])];
				//modifiers1 = [new BorderModifier(0xffcc0000),new GlowModifier(0xffcc0000)];
				target1.setProperty(new PropertyReference("@render.modifiers"), modifiers1);
				var modifiers2:Array = target2.getProperty(new PropertyReference("@render.modifiers"));
				//modifiers2.push(new BlurModifier());
				//modifiers2 = [new BorderModifier(0xffcc0000),new GlowModifier(0xffcc0000)];
				modifiers2 = [new ColorizeModifier([0.5, 0.5, 0.5, 0, 0], [0.5, 0.5, 0.5, 0, 0], [0.5, 0, 0, 0, 0], [0, 0, 0, 1, 0])];
				target2.setProperty(new PropertyReference("@render.modifiers"), modifiers2);
			}catch(e:Error){}
			n_counter = 0;
			
		}
		
		private function onGameResumed(e:Event):void 
		{
			timer.start();
		}
		
		private function onGamePaused(e:Event):void 
		{
			timer.stop();
		}
		
		private function onTimer(e:TimerEvent):void 
		{
			n_counter += 1;
			if (n_counter == 2) {
				try {
					if (target1 != null&&target2!=null) {
						PBE.mainStage.dispatchEvent(new Event("HINT_FINISHED"));
					}
				}catch(e:Error){}
			}
			if (n_counter == 10) {
				n_counter = 0;
				try {
					///trace("reset nih !");
					var modifiers1:Array = target1.getProperty(new PropertyReference("@render.modifiers"));
					//modifiers1 = [new GlowModifier(0xff000000)];
					modifiers1 = [];
					target1.setProperty(new PropertyReference("@render.modifiers"), modifiers1);
					var modifiers2:Array = target2.getProperty(new PropertyReference("@render.modifiers"));
					//modifiers2.push(new BlurModifier());
					//modifiers2 = [new BorderModifier(0xff000000)];
					modifiers2 = [];
					target2.setProperty(new PropertyReference("@render.modifiers"), modifiers2);
				
				}catch (e:Error) {
					
				}
			}
			//PBE.log(this, ""+GAME_TIME);
			GAME_TIME += 1;
			
			//PBE.log(this, "Frame : " + Math.round((GAME_TIME / total_time) * 60));
			panel.panel.timebar.gotoAndStop(Math.round((GAME_TIME / total_time) * 60));
			panel.panel.txt_time.text = (TimeUtil.getTime(total_time-GAME_TIME));
			
			var status:IEntity = PBE.lookupEntity("status");
			status.setProperty(new PropertyReference("@puzzle_status.time_left"), (total_time-GAME_TIME));
			if (GAME_TIME > total_time) {
				PBE.mainStage.dispatchEvent(new Event("GAME_STOPED"));
				PBE.mainStage.dispatchEvent(new Event("LOSE"));
			}
		}
		private function onGameStoped(e:Event):void 
		{
			this.timer.stop();
		}
		
		private function onGameStarted(e:Event):void 
		{
			timer.start();
		}
		public override function onTick(deltaTime:Number):void {
			super.onTick(deltaTime);

			var status:IEntity = PBE.lookupEntity("status");
			var n_status:int = status.getProperty(new PropertyReference("@puzzle_status.n_hint"));
			//PBE.log(this, "hint : "+n_status);
			try {
				hint.txt.text = n_status.toString();
				n_hint = n_status;
			}catch (e:Error) {
				
			}
		}
		protected override function onRemove():void {
			super.onRemove();
			PBE.mainStage.removeEventListener("GAME_STARTED", onGameStarted);
			PBE.mainStage.removeEventListener("GAME_STOPED", onGameStoped);
		}
		
	}

}