<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SBaseController extends CController {

/**
 * Checks if srbac access is granted for the current user
 * @param <String> $action . The current action
 * @return <boolean> true if access is granted else false
 */
    protected function beforeAction($action) {
        parent::beforeAction($action);
        //srbac access
        $access =  ucfirst($this->id).ucfirst($this->action->id);

        //Always allow access if $access is in the allowedAccess array
        if(in_array($access, $this->allowedAccess())) {
            return true;
        }

        //Allow access if srbac is not installed yet
        if(!Yii::app()->getModule('srbac')->isInstalled()) {
            return true;
        }

        
        // Check for srbac access

        
        if(!Yii::app()->user->checkAccess($access)) {
        // You may change this messages
            $error["code"] = "403";
            $error["title"] = "You are not authorized for this action";
            $error["message"] = "Error while trying to access " .$this->id."/".$this->action->id."." ;
            //You may change the view for unauthorized access
            if(Yii::app()->request->isAjaxRequest) {
                $this->renderPartial(Yii::app()->getModule('srbac')->notAuthorizedView,array("error"=>$error));
            } else {
                $this->render(Yii::app()->getModule('srbac')->notAuthorizedView,array("error"=>$error));
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * an array holding the items that are always allowed
     * @return <Array>
     */
    private function allowedAccess() {
        return Yii::app()->getModule('srbac')->alwaysAllowed;
    }
}
?>
