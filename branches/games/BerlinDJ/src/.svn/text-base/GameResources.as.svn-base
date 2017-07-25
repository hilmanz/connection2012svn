package  
{
	import com.pblabs.engine.resource.MP3Resource;
	import com.pblabs.engine.resource.ResourceBundle;
	import com.pblabs.engine.PBE;
	import com.pblabs.engine.resource.ImageResource;
	/**
	 * ...
	 * @author duf
	 */
	public class GameResources extends ResourceBundle 
	{
		[Embed(source="../assets2/images/bg_play_game2.png")]
		public var bg_game:Class;
		
		[Embed(source = "../assets2/images/disc_blue.png")]
		public var disc_blue:Class;
		
		[Embed(source = "../assets2/images/disc_red.png")]
		public var disc_red:Class;
		
		[Embed(source = "../assets2/images/disc_green.png")]
		public var disc_green:Class;
		
		[Embed(source = "../assets2/images/disc_click_blue.png")]
		public var disc_click_blue:Class;
		
		[Embed(source = "../assets2/images/disc_click_red.png")]
		public var disc_click_red:Class;
		
		[Embed(source = "../assets2/images/disc_click_green.png")]
		public var disc_click_green:Class;
		
		
		[Embed(source = "../assets2/Level1.mp3")]
		public var bgm1:Class;
		[Embed(source = "../assets2/Level2.mp3")]
		
		public var bgm2:Class;
		[Embed(source = "../assets2/Level3.mp3")]
		public var bgm3:Class;
		
		public function GameResources() 
		{
			
			super();
			PBE.resourceManager.registerEmbeddedResource('../assets2/images/bg_play_game2.png', ImageResource, new bg_game());
			PBE.resourceManager.registerEmbeddedResource('../assets2/images/disc_blue.png', ImageResource, new disc_blue());
			PBE.resourceManager.registerEmbeddedResource('../assets2/images/disc_red.png', ImageResource, new disc_red());
			PBE.resourceManager.registerEmbeddedResource('../assets2/images/disc_green.png', ImageResource, new disc_green());
			
			PBE.resourceManager.registerEmbeddedResource('../assets2/images/disc_click_green.png', ImageResource, new disc_click_green());
			PBE.resourceManager.registerEmbeddedResource('../assets2/images/disc_click_red.png', ImageResource, new disc_click_red());
			PBE.resourceManager.registerEmbeddedResource('../assets2/images/disc_click_blue.png', ImageResource, new disc_click_blue());
			
			PBE.resourceManager.registerEmbeddedResource('../assets2/Level1.mp3', MP3Resource, new bgm1());
			PBE.resourceManager.registerEmbeddedResource('../assets2/Level2.mp3', MP3Resource, new bgm2());
			PBE.resourceManager.registerEmbeddedResource('../assets2/Level3.mp3',MP3Resource,new bgm3());
		}
	}

}