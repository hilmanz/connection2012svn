package
{
	import com.pblabs.engine.PBE;
	import com.pblabs.engine.resource.ResourceBundle;
	import com.pblabs.engine.resource.ImageResource;
	import com.pblabs.engine.resource.XMLResource;
	public class Resources extends ResourceBundle
	{
		
		
		
		
		[Embed(source = "../assets/bg_game.jpg")]
		public var bg_game:Class;
		
		[Embed(source = "../assets/bg_game.png",compression=true,quality=60)]
		public var bg_game2:Class;
		
		[Embed(source = "../assets/bg_wall.png",compression=true,quality=60)]
		public var bg_wall:Class;
		
		[Embed(source = "../assets/gravity1.png",compression=true,quality=60)]
		public var gravity1:Class;
		
		[Embed(source = "../assets/gravity2.png",compression=true,quality=60)]
		public var gravity2:Class;
		
		[Embed(source = "../assets/gravity3.png",compression=true,quality=60)]
		public var gravity3:Class;
		
		public function Resources():void {
			//PBE.resourceManager.registerEmbeddedResource('../assets/Level1.xml', XMLResource, new LevelDescriptions());
			//PBE.resourceManager.registerEmbeddedResource('../assets/LevelDescriptions.xml', XMLResource, new LevelDescriptions());
			PBE.resourceManager.registerEmbeddedResource('../assets/bg_wall.png', ImageResource, new bg_wall());
			PBE.resourceManager.registerEmbeddedResource('../assets/gravity1.png', ImageResource, new gravity1());
			PBE.resourceManager.registerEmbeddedResource('../assets/gravity2.png', ImageResource, new gravity2());
			PBE.resourceManager.registerEmbeddedResource('../assets/gravity3.png', ImageResource, new gravity3());
			PBE.resourceManager.registerEmbeddedResource('../assets/bg_game.jpg', ImageResource, new bg_game());
			super();
		}
	}
}