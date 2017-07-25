package  
{
	import br.com.stimuli.loading.loadingtypes.VideoItem;
	import com.pblabs.animation.AnimatorComponent;
	import com.pblabs.engine.components.AnimatedComponent;
	import com.pblabs.engine.components.TickedComponent;
	import com.pblabs.engine.core.ITickedObject;
	import com.pblabs.engine.entity.Entity;
	import com.pblabs.engine.entity.EntityComponent;
	import com.pblabs.engine.entity.PropertyReference;
	import com.pblabs.engine.entity.IEntity;
	import com.pblabs.engine.PBE;
	import com.pblabs.rendering2D.ui.PBLabel;
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.geom.Point;
	import com.greensock.*;
	import com.greensock.easing.*;
	import com.pblabs.sound.BackgroundMusicComponent;
	import flash.utils.Timer;
	import flash.ui.Keyboard;
	/**
	 * ...
	 * @author duf
	 */
	public class GameTickComponent extends TickedComponent implements ITickedObject
	{
		private var music_countdown:int = 0;
		private var n_tick:int = 0;
		private var tween_time:Number = 2;
		private var metronome:int = 1;
		private var beat:int = 0;
		private var is_bgm:Boolean = false;
		private var time:Timer;
		private var bpm:int = 150;
		//private var bpm:int = 150;
		var bgm:BackgroundMusicComponent;
		private var ms:Number = (Math.round(1000 / (bpm / 60)) / 2);
		private var n_ms:Number = 0;
		private var speed:Number = 0; // 10 pixels per tick
		//private var speed:Number = 330; //10 pixels per tick
		private var g:Array = [];
		private var r:Array = [];
		private var b:Array = [];
		private var stack:Array = [];
		private static var KEY_A:uint = 65;
		private static var KEY_S:uint = 83;
		private static var KEY_D:uint = 68;
		private var animGreen:IEntity;
		private var animRed:IEntity;
		private var animBlue:IEntity;
		private var points:Number = 0;
		private var positionReference:PropertyReference = new PropertyReference("@Spatial.position");
		private var habis:Boolean = false;
		private var debug:PBLabel;
		private var max_score:int = 0;
		public function GameTickComponent() 
		{
			super();
			
		}
		protected override function onAdd():void {
			super.onAdd();
			debug = new PBLabel();
			debug.fontColor = 0x000000;
			debug.caption = "test aja";
			debug.refresh();
			debug.x = 10;
			debug.y = 300;
			debug.width = 600;
			//PBE.mainStage.addChild(debug);
			stack = owner.getProperty(new PropertyReference("@level.stack"));
			speed = owner.getProperty(new PropertyReference("@level.speed"));
			max_score = owner.getProperty(new PropertyReference("@level.max_score"));
			PBE.mainStage.dispatchEvent(new Event("NEW_LEVEL"));
			var position:Point = new Point();
			
			animGreen = PBE.templateManager.instantiateEntity('animGreen');
			
			position.x = -85;
			position.y = 90;
			animGreen.setProperty(positionReference, position);
			
			animRed = PBE.templateManager.instantiateEntity('animRed');
			
			position.x = 0;
			position.y = 90;
			animRed.setProperty(positionReference, position);
			
			animBlue = PBE.templateManager.instantiateEntity('animBlue');
			position.x = 85;
			position.y = 90;
			animBlue.setProperty(positionReference, position);
			
			habis = false;
		}
		

		
		protected override function onRemove():void {
			super.onRemove();
			while (r.length > 0) {
				var o:IEntity = r.pop();
				o.destroy();
			}
			while (g.length > 0) {
				var o2:IEntity = g.pop();
				o2.destroy();
			}
			while (b.length > 0) {
				var o3:IEntity = b.pop();
				o3.destroy();
			}
			o = null;
			o2 = null;
			o3 = null;
			//bgm.stop();

			this.owner.destroy();
		}
	
		
		public override function onTick(tickDelta:Number):void {
			super.onTick(tickDelta);
			n_ms += tickDelta;
			
			
			updatePositions(tickDelta);
			//trace(n_ms + " vs " + (ms/1000));
			if (n_ms >= (ms/1000)) {
				//trace("tick nih");
				n_ms = 0;
				spawn();
			}
			
			if (music_countdown > 75){
				play_music();
			}else {
				music_countdown++;
			}
			//updatePositions();
			checkInput();
			//PBE.log(this, "Score -> " + points);
			check_if_game_end();
			update_cue_pos();
		}
		private function checkInput():void {
			if (PBE.inputManager.keyJustPressed(KEY_A)) {
				//trace("A");
				
				if (g.length > 0) {
					if (getPoints(g[0].getProperty(positionReference).y as Number) == 1) {
						g[0].setProperty(new PropertyReference("@Status.state"),1);
					}
				}
				var c1:AnimatorComponent = animGreen.lookupComponentByName('FrameAnimation') as AnimatorComponent;
				c1.play('idle', 0);
			}
			if (PBE.inputManager.keyJustPressed(KEY_S)) {
				//trace("S");
				if (r.length > 0) {
					if (getPoints(r[0].getProperty(positionReference).y as Number) == 1) {
						r[0].setProperty(new PropertyReference("@Status.state"),1);
					}
				}
				var c2:AnimatorComponent = animRed.lookupComponentByName('FrameAnimation') as AnimatorComponent;
				c2.play('idle', 0);
			}
			if (PBE.inputManager.keyJustPressed(KEY_D)) {
				//trace("D");
				if (b.length > 0) {
					if (getPoints(b[0].getProperty(positionReference).y as Number) == 1) {
						b[0].setProperty(new PropertyReference("@Status.state"),1);
					}
				}
				var c3:AnimatorComponent = animBlue.lookupComponentByName('FrameAnimation') as AnimatorComponent;
				c3.play('idle', 0);
			}
		}
		private function getPoints(pos:Number):int {
			var oldPoints:Number = points;
			if (pos < 0) {
				points -= 10;
			}else if (pos > 0 && pos < 46) {
				points -= 5;
			}else if (pos >= 46 && pos <= 50) {
				points += 1;
				return 1;
			}else if (pos > 50 && pos < 85) {
				points += 10;
				return 1;
			}else if (pos > 85 && pos < 90) {
				points += 10;
				return 1;
			}else if (pos > 90 && pos < 100) {
				points += 5;
				return 1;
			}else {
				points += -5;
			}
			if (points < 0) {
				points = 0;
			}
			return 0;
		}
		
		private function update_cue_pos():void 
		{	try{
				owner.setProperty(new PropertyReference("@level.total_score"), points);
				PBE.mainStage.dispatchEvent(new Event("SCORE_UPDATED"));
			}catch(e:Error){}
		}
		private function updatePositions(tickDelta:Number):void 
		{
			debug.caption = "";
			debug.caption += "Score : " + points + " ";
			updateGreen(tickDelta);
			updateRed(tickDelta);
			updateBlue(tickDelta);
			debug.refresh();
		}
		
		private function updateGreen(tickDelta:Number):void 
		{
			var removes:Array = [];
			if(g.length>0){
				for(var i:* in g) {
					try {
						var position:Point = g[i].getProperty(positionReference);
						position.y += speed * tickDelta;
						if(i==0){
							debug.caption += "G: " + Math.round(position.y);
						}
						if (position.y > 120) {
							if (g[i].getProperty(new PropertyReference("@Status.state")) == 0) {
								points -= 10;
							}
							g[i].destroy();
							removes.push(i);
							
						}else{
							g[i].setProperty(positionReference, position);
						}
					}catch (e:Error) {
					//	trace(e.message);
					}
				}
				for (var j:* in removes) {
					//trace('g ' + removes[j] + ' removed');
					g.splice(removes[j], 1);
				}
			}else {
				//trace("green kosong");
			}
		}
		private function updateRed(tickDelta:Number):void 
		{
			var removes:Array = [];
			if(r.length>0){
				for(var i:* in r) {
					try {
						var position:Point = r[i].getProperty(positionReference);
						position.y += speed * tickDelta;
						if(i==0){
						debug.caption += "R: " + Math.round(position.y);
						}
						if (position.y > 120) {
							if (r[i].getProperty(new PropertyReference("@Status.state")) == 0) {
								points -= 10;
							}
							r[i].destroy();
							removes.push(i);
							
						}else{
							//trace(position.y);
							r[i].setProperty(positionReference, position);
						}
					}catch (e:Error) {
						//trace(e.message);
					}
				}
				for (var j:* in removes) {
					//trace('r ' + removes[j] + ' removed');
					r.splice(removes[j], 1);
				}
			}else {
				//trace("red kosong");
			}
		}
		private function updateBlue(tickDelta:Number):void 
		{
			var removes:Array = [];
			if (b.length > 0) {
				for(var i:* in b) {
					try {
						var position:Point = b[i].getProperty(positionReference);
						position.y += speed * tickDelta;
						if(i==0){
						debug.caption += "B: " + Math.round(position.y);
						}
						//trace(position.y);
						if (position.y > 120) {
							if (b[i].getProperty(new PropertyReference("@Status.state")) == 0) {
								points -= 10;
							}
							b[i].destroy();
							removes.push(i);
							
						}else{
							b[i].setProperty(positionReference, position);
						}
					}catch (e:Error) {
					//	trace(e.message);
					}
				}
				for (var j:* in removes) {
					//trace('b ' + removes[j] + ' removed');
					b.splice(removes[j], 1);
				}
			}else {
				//trace("blue kosong");
			}
		}
		public function check_if_game_end():void {
		
			if (habis) {
				if (g.length == 0 && r.length == 0 && b.length == 0) {
					//PBE.log(this, "LEVEL COMPLETE NIH !");
					game_over();
				}else {
					//PBE.log(this, "LEVEL BELUM COMPLETE");
				}
			}
		}
		private function game_over():void {
			//hitung score
			owner.setProperty(new PropertyReference("@level.total_score"), points);
			
			PBE.mainStage.dispatchEvent(new Event("LEVEL_COMPLETE"));
		}
		public function spawn():void {
			if(stack.length>0){
				var arr:Array = stack.shift();
				if (arr[0] == 1) {
					//trace("SPAWN GREEN");
					spawnGreen();
				}
				if (arr[1] == 1) {
				//	trace("SPAWN RED");
					spawnRed();
				}
				if (arr[2] == 1) {
					//trace("SPAWN BLUE");
					spawnBlue();
				}
			}else {
				habis = true;
				//trace("kosong");
			}
		}
		private function spawnGreen():void {
			var entity:IEntity = PBE.templateManager.instantiateEntity('green');
			var position:Point = new Point();
			position.x = -85;
			position.y = -400;
			entity.setProperty(positionReference, position);
			g.push(entity);
		}
		private function spawnRed():void {
			var entity:IEntity = PBE.templateManager.instantiateEntity('red');
			var position:Point = new Point();
			position.x = 0;
			position.y = -400;
			entity.setProperty(positionReference, position);
			r.push(entity);
		}
		private function spawnBlue():void {
			var entity:IEntity = PBE.templateManager.instantiateEntity('blue');
			var position:Point = new Point();
			position.x = 85;
			position.y = -400;
			entity.setProperty(positionReference, position);
			b.push(entity);
		}
		
		
		private function play_music():void {
			bgm = PBE.lookupComponentByName(owner.name,"bgm") as BackgroundMusicComponent;
			bgm.start();
			is_bgm = true;
		}
		
		
	}

}