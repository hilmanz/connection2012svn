package  
{
	import flash.display.DisplayObject;
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.ProgressEvent;
	import flash.utils.*;
	import com.pblabs.engine.PBE;
	import com.pblabs.rendering2D.ui.PBLabel;
	
	/**
	 * ...
	 * @author duf
	 */
	public class Preloader extends MovieClip 
	{
		var lbl:PBLabel;
		var mc:mc_preloader;
		var loaded:Number = 0;
		var total:Number = 0;
		public function Preloader() 
		{
			super();
			trace("preloader start");
			//lbl = new PBLabel();
			//lbl.caption = "Loading ...";
			//lbl.fontColor = 0x000000;
			//lbl.width = 600;
			//lbl.refresh();
			//addChild(lbl);
			
			mc = new mc_preloader();
			mc.mc.gotoAndStop(1);
			mc.txt_percent.text = "0 %";
			addChild(mc);
			
			loaderInfo.addEventListener(ProgressEvent.PROGRESS, onProgress, false, 0, true);
			addEventListener(Event.ENTER_FRAME, onFrames, false, 0, true);
		}
		
		private function onFrames(e:Event):void 
		{
			//trace(currentFrame + " of " + totalFrames);
			if (currentFrame == totalFrames && total>0&& loaded==total) {
				finish_loading();
			}
		}
		
		private function finish_loading():void 
		{
			removeEventListener(Event.ENTER_FRAME, onFrames);
			loaderInfo.removeEventListener(ProgressEvent.PROGRESS, onProgress);
			stop();
			startup();
		}
		private function startup():void {
			 var mainClass:Class = getDefinitionByName("Main") as Class;
			 stage.addChild(new mainClass() as DisplayObject);
			 this.visible = false;
			 this.stop();
			 //if (parent == stage) stage.addChildAt(new mainClass() as DisplayObject, 0);
			 //else addChildAt(new mainClass() as DisplayObject, 0);
		}
		private function onProgress(e:ProgressEvent):void 
		{
			var prog:Number = e.bytesLoaded / e.bytesTotal;
			loaded = e.bytesLoaded;
			total = e.bytesTotal;
			var percent:Number = Math.round(prog*100);
			var n_frame:Number = Math.round(prog*60);
			
			mc.mc.gotoAndStop(n_frame);
			mc.txt_percent.text = percent.toString()+" %";
			//trace(e.bytesLoaded + " / " + e.bytesTotal);
			//lbl.caption = "Loading : " + e.bytesLoaded + " / " + e.bytesTotal;
			//lbl.refresh();
			if (e.bytesLoaded == e.bytesTotal && e.bytesTotal > 4) {
				
				//parent.removeChild(this);
				/*var mainClass:Class = getDefinitionByName("Main") as Class;
				 if (parent == stage) 
					stage.addChildAt(new mainClass() as DisplayObject, 0);
				 else 
					addChildAt(new mainClass() as DisplayObject, 0);
				*/
				//nextFrame();
				//parent.removeChild(this);
			}
		}
		
	}

}