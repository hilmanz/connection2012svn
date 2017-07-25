package  
{
	import br.com.stimuli.loading.loadingtypes.VideoItem;
	import com.pblabs.engine.components.AnimatedComponent;
	import com.pblabs.engine.components.TickedComponent;
	import com.pblabs.engine.core.ITickedObject;
	import com.pblabs.engine.entity.EntityComponent;
	import com.pblabs.engine.entity.PropertyReference;
	import com.pblabs.engine.entity.IEntity;
	import com.pblabs.engine.PBE;
	import flash.events.TimerEvent;
	import flash.geom.Point;
	import com.greensock.*;
	import com.greensock.easing.*;
	import com.pblabs.sound.BackgroundMusicComponent;
	import flash.utils.Timer;
	/**
	 * ...
	 * @author duf
	 */
	public class ItemStatus extends EntityComponent
	{
		
		public var state:int = 0; //0 --> blm di hit di goal area
		
		public function ItemStatus() 
		{
			super();
			
		}
		protected override function onAdd():void {
			super.onAdd();
			
		
		}
		protected override function onRemove():void {
			super.onRemove();
		}
		
		
	}

}