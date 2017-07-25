package  
{
	import com.pblabs.engine.components.TickedComponent;
	import com.pblabs.engine.entity.IEntity;
	import com.pblabs.engine.entity.PropertyReference;
	import com.pblabs.engine.PBE;
	import com.pblabs.rendering2D.SpriteRenderer;
	import com.pblabs.sound.BackgroundMusicComponent;
	import flash.geom.Point;
	
	/**
	 * ...
	 * @author Kanadigital
	 */
	public class GameControllerComponent extends TickedComponent 
	{
		private var current_level:int = 0;
		private var positionReference:PropertyReference = new PropertyReference("@Spatial.position");
		private var r:Array = [];
		private var g:Array = [];
		private var b:Array = [];
		private var rout:Array = [];
		private var gout:Array = [];
		private var bout:Array = [];
		private var r_index:int = 0;
		private var g_index:int = 0;
		private var b_index:int = 0;
		private var r_spawn:int = 0;
		private var g_spawn:int = 0;
		private var b_spawn:int = 0;
		private var _speed:Number = 0;
		private var _tempo:Number = 90;
		private var _counter:Number = 0;
		private var _spawn_limit:Number = 0;
		private var _total_objects:Number = 0;
		private var _music_start_after:int = 0;
		private var _queue:Array = [[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 1],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 1],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 1],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 1],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 1],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[0, 0, 1],
									[0, 0, 0],
									[1, 0, 0],
									[0, 0, 0],
									[0, 0, 0],
									[1, 0, 0],
									];
		public function GameControllerComponent() 
		{
			super();
			
		}
		protected override function onAdd():void {
			super.onAdd();
			PBE.log(this, 'Game Controller is on.. for level ' + current_level);
			PBE.log(this, 'generating objects');
			createEverything();
			spawn();
		}
		
		private function createEverything():void 
		{
			/*
			for (var i:int = 0; i < total_objects; i++) {
				var red:IEntity = PBE.templateManager.instantiateEntity('red');
				var position:Point = new Point(0, 0);
				position.x = 0;
				position.y = -320;
				red.setProperty(positionReference, position);
				r.push(red);
				
				var green:IEntity = PBE.templateManager.instantiateEntity('green');
				position.x = -85;
				position.y = -320;
				green.setProperty(positionReference, position);
				g.push(green);
				
				var blue:IEntity = PBE.templateManager.instantiateEntity('blue');
				position.x = 85;
				position.y = -320;
				blue.setProperty(positionReference, position);
				b.push(blue);
			}
			
			PBE.log(this, r.toString());
			PBE.log(this, g.toString());
			PBE.log(this, b.toString());
			*/
		}
		protected override function onRemove():void {
			super.onRemove();
		}
		/*
		public override function onTick(deltaTime:Number):void {
			super.onTick(deltaTime);
			
			if (music_start_after > 0) {
				music_start_after--;
			}else {
				//play music
				var bgm:BackgroundMusicComponent = PBE.lookupComponentByName(owner.name,"bgm") as BackgroundMusicComponent;
				//bgm.start();
			}
			if (counter == tempo) {
				//spawn();
				counter = 0;
			}else {
				counter++;
			}
			//update_position();
		}
		*/
		public override function onTick(deltaTime:Number):void {
			super.onTick(deltaTime);
			
			if (music_start_after > 0) {
				music_start_after--;
			}else {
				//play music
				var bgm:BackgroundMusicComponent = PBE.lookupComponentByName(owner.name,"bgm") as BackgroundMusicComponent;
				//bgm.start();
			}
			if (counter == tempo) {
				//spawn();
				counter = 0;
			}else {
				counter++;
			}
			//update_position();
		}
		private function spawn():void {
			if (queue.length > 0) {
				var line:Array = queue.pop();
				//PBE.log(this, line.toString());
				if (line[0] == 1) {
					//ambil green dot dari stock
					if (g.length > 0) {
						PBE.log(this, 'pop green');
						
						gout.push(g.pop());
					}
				}
				if (line[1] == 1) {
					//ambil green dot dari stock
					if (r.length > 0) {
						PBE.log(this, 'pop red');
						rout.push(r.pop());
					}
				}
				if (line[2] == 1) {
					//ambil green dot dari stock
					if (b.length > 0) {
						PBE.log(this, 'pop blue');
						bout.push(b.pop());
					}
				}
			}
			//PBE.log(this, 'spawn');
			/*
			r_spawn++;
			if (r_spawn > spawn_limit) {
				r_spawn = spawn_limit;
			}else {
				var red:IEntity = PBE.templateManager.instantiateEntity('red');
				var position:Point = new Point(0, 0);
				position.x = 0;
				position.y = -320;
				red.setProperty(positionReference, position);
				r.push(red);
			}
			g_spawn++;
			if (g_spawn > spawn_limit) {
				g_spawn = spawn_limit;
			}else {
				var green:IEntity = PBE.templateManager.instantiateEntity('green');
				
				position.x = -85;
				position.y = -320;
				green.setProperty(positionReference, position);
				g.push(green);
			}
			
			b_spawn++;
			if (b_spawn > spawn_limit) {
				b_spawn = spawn_limit;
			}else {
				var blue:IEntity = PBE.templateManager.instantiateEntity('blue');
				position.x = 85;
				position.y = -320;
				blue.setProperty(positionReference, position);
				b.push(blue);
			}
			*/
		}
		private function update_position():void 
		{
			var position:Point = new Point(0, 0);
			var nr:int = 0;
			var ng:int = 0;
			var nb:int = 0;
			for each(var red:Object in rout) {
				var ored:SpriteRenderer = red.lookupComponentByName("render") as SpriteRenderer;
				ored.alpha = 1.0;
				position = red.getProperty(positionReference);
				if (position.y < 290) {
					position.y += _speed;
					red.setProperty(positionReference, position);
				}else {
					//dot ini sudah melewati batas layar.. keluarkan dari r-out.. dan kembalikan ke stock.
					position.y = -320;
					ored.alpha = 0;
					r.push(rout.splice(nr, 1)[0]);
					red.setProperty(positionReference, position);
					
				}
				red.setProperty(positionReference, position);
				nr++;
			}
			for each(var green:Object in gout) {
				position = green.getProperty(positionReference);
				var og:SpriteRenderer = green.lookupComponentByName("render") as SpriteRenderer;
				og.alpha = 1.0;
				//og.unregister();
				if (position.y < 290) {
					position.y += _speed;
					green.setProperty(positionReference, position);
				}else {
					//dot ini sudah melewati batas layar.. keluarkan dari r-out.. dan kembalikan ke stock.
					position.y = -320;
					og.alpha = 0;
					
					g.push(gout.splice(ng, 1)[0]);
					green.setProperty(positionReference, position);
				}
				
				ng++;
			}
			for each(var blue:Object in bout) {
				position = blue.getProperty(positionReference);
				var oblue:SpriteRenderer = blue.lookupComponentByName("render") as SpriteRenderer;
				oblue.alpha = 1.0;
				if (position.y < 290) {
					position.y += _speed;
					blue.setProperty(positionReference, position);
					
				}else {
					//dot ini sudah melewati batas layar.. keluarkan dari r-out.. dan kembalikan ke stock.
					oblue.alpha = 0;
					b.push(bout.splice(nb, 1)[0]);
					position.y = -320;
					blue.setProperty(positionReference, position);
					
				}
				
				nb++;
			}
			
		}
		
		public function get current():int 
		{
			return current_level;
		}
		
		public function set current(value:int):void 
		{
			current_level = value;
		}
		
		public function get speed():Number 
		{
			return _speed;
		}
		
		public function set speed(value:Number):void 
		{
			_speed = value;
		}
		
		public function get tempo():Number 
		{
			return _tempo;
		}
		
		public function set tempo(value:Number):void 
		{
			_tempo = value;
		}
		
		public function get counter():Number 
		{
			return _counter;
		}
		
		public function set counter(value:Number):void 
		{
			_counter = value;
		}
		
		public function get spawn_limit():Number 
		{
			return _spawn_limit;
		}
		
		public function set spawn_limit(value:Number):void 
		{
			_spawn_limit = value;
		}
		
		public function get total_objects():Number 
		{
			return _total_objects;
		}
		
		public function set total_objects(value:Number):void 
		{
			_total_objects = value;
		}
		
		public function get queue():Array 
		{
			return _queue;
		}
		
		public function set queue(value:Array):void 
		{
			_queue = value;
		}
		
		public function get music_start_after():int 
		{
			return _music_start_after;
		}
		
		public function set music_start_after(value:int):void 
		{
			_music_start_after = value;
		}
	}

}