package  
{
	import com.pblabs.engine.PBE;
	import com.pblabs.engine.components.DataComponent;
	import com.pblabs.engine.entity.IEntity;
	import flash.events.Event;
	
	/**
	 * ...
	 * @author Kanadigital
	 */
	public class PuzzleStatus extends DataComponent 
	{
		public var isBusy:Boolean = false;
		public var selected_piece:IEntity = null;
		public var positions:Object = {};
		public var stack:Array = [];//stack puzzle..
		public var isFinished:Boolean = false;
		public var n_hint:int = 2;
		public var hint:mc_hint;
		public var switch_stack:Array = [];
		public var isPaused:Boolean = false;
		public var level:int = 0;
		public var steps:int = 0;
		public var time_left:int = 0;
		public function PuzzleStatus() 
		{
			super();
		}
		protected override function onAdd():void {
			super.onAdd();
			PBE.mainStage.addEventListener("HINT", onHint, false, 0, true);
			PBE.log(this, "Running");
		}
		
		private function onHint(e:Event):void 
		{
			PBE.log(this, "hint nih");
		}
		protected override function onRemove():void {
			super.onRemove();
			PBE.log(this, "removed");
		}
	}

}