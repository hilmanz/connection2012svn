package  
{
	import com.pblabs.engine.entity.EntityComponent;
	import com.pblabs.engine.PBE;
	
	/**
	 * ...
	 * @author Kanadigital
	 */
	public class ConfigComponent extends EntityComponent 
	{
		public var remote_url:String = "";
		public function ConfigComponent() 
		{
			super();
		}
		protected override function onAdd():void {
			super.onAdd();
			PBE.log(this, "ConfigComponent loaded");
		}
		protected override function onRemove():void {
			super.onRemove();
		}
		
	}

}