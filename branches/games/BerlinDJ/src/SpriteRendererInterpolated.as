package  
{
   import com.pblabs.engine.core.ProcessManager;
   import com.pblabs.engine.entity.PropertyReference;
   import com.pblabs.engine.PBE;
   import com.pblabs.rendering2D.SpriteRenderer;
   import flash.geom.Point;
   
   /**
    * ...
    * @author Makiyivka
    */
   public class SpriteRendererInterpolated extends SpriteRenderer 
   {
      //A reference to the spatial component's position.
      public var spatialPosProperty:PropertyReference;
      //The position that we 'should' be at
      public var curPos:Point;   
      //Our previous position
      public var lastPos:Point;   
      //The last recorded interpolation factor, or how far between ticks we are 
      public var lastIterpolationFactor:Number;
      
      public function SpriteRendererInterpolated() 
      {
         super();
         
      }
      
      override protected function onReset():void {
         super.onReset();
         
         //Make sure that all of the new variables are set.
         curPos = owner.getProperty(spatialPosProperty) as Point;
         lastPos = curPos.clone();
         position = curPos.clone();
         lastIterpolationFactor = 0;
      }
      
      override public function onFrame(elapsed:Number):void
      {
         super.onFrame(elapsed);
         
         //If the current interpolation factor is less than our stored one, then 
         //a new tick has happened, and we need to adjust our position variables
         if ( PBE.processManager.interpolationFactor < lastIterpolationFactor) {
            lastPos = curPos.clone();
            curPos = owner.getProperty(spatialPosProperty) as Point;
         }
         
         //If our last known position is different than our current position, 
         //then interpolate between the two based on the processmanager's
         //interpolation factor.
         if (!lastPos.equals(curPos)) {
            var newX:int = lastPos.x + (PBE.processManager as ProcessManager).interpolationFactor * (curPos.x - lastPos.x);
            var newY:int = lastPos.y + (PBE.processManager as ProcessManager).interpolationFactor * (curPos.y - lastPos.y);
            position = new Point(newX, newY);
         }
         
         lastIterpolationFactor = PBE.processManager.interpolationFactor;
      }
   }
}