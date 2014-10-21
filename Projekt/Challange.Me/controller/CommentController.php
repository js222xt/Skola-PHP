<?php

namespace controller;

class CommentController{
	
	/** @var \view\ApplicationView */
	private $applicationView;
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	
	public function __construct($appView, $appDAL){
		$this->applicationView = $appView;
		$this->applicationDAL = $appDAL;
	}
	
	public function PostComment($loggedInUser){
								
		// Get challenge ID
		$ID = $this->applicationView->GetSeeChallengeIDFromGet();	
		
		// Check if valid
		if($ID != -1 && is_numeric($ID)){
			$errorsFound = false;
			// Check if stuff got posted
			if($this->applicationView->UserHavePostedCommentCorrectly()){
				// Validate
				// Get title
				$title = $this->applicationView->GetCommentTitle();
				// Get comment
				$comment = $this->applicationView->GetCommentComment();
				
				// Check length
				if( strlen($title) == 0 ||
					strlen($title) > 50){
					$this->applicationView->TitleIsInvalid();
					$errorsFound = true;
				}
				else if(strlen($comment) == 0 ||
						strlen($comment) > 250){
					$this->applicationView->CommentIsInvalid();	
					$errorsFound = true;
				}
						
				// Errors?
				if(!$errorsFound){
					// Add comment to Database
					$this->applicationDAL->AddCommentToChallenge($ID, $loggedInUser[0]['ID'], $title, $comment);
					$this->applicationView->AddCommentToChallengeSucess();
				}
			}
		}
		else{
			$this->applicationView->NoCommentWithIDFound();
		}
	}	
	
	public function RemoveComment($loggedInUser){
		//Get comment ID
		$CID = $this->applicationView->GetRemoveCommentIDFromGet();
		if($CID != -1 && is_numeric($CID)){

			$comment = $this->applicationDAL->GetComment($CID);

			if(count($comment) == 1){				
				if(count($loggedInUser) == 1){
					// Is it o;ur user?
					if($comment[0]['AID'] == $loggedInUser[0]['ID']){
						$this->applicationDAL->RemoveComment($CID);
						
						$this->applicationView->AddCommentRemoveSucess();
					}
					else {
						$this->applicationView->UserDoesNotOwnComment();
					}	
				}
				else {
					$this->applicationView->NoUserFound();
				}
			}
			else {
				$this->applicationView->CommentNotFound();
			}											
		}
		else {
			$this->applicationView->CommentNotFound();
		}
	}	
}
