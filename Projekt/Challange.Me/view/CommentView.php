<?php


namespace view;

class CommentView{
	
	/** @var String */
	private $addCommentOnCommentString = "AddCommentToCommentID";
	/** @var String */
	private $addCommentOnChallengeString = "AddCommentToChallengeID";
	/** @var String */
	private $removeCommentString = "RemoveCommentID";
	/** @var String */
	private $titleString = "title";
	/** @var String */
	private $commentString = "comment";
	/** @var String */
	private $userIDString = "userID";
	
	
	/**
	 * @return HTML for writing a comment
	 */
	public function WriteAddCommentHTML($challengeView){
		$html = "
			<p> What do you think? </p>
			<form action='index.php?". $challengeView->GetSeeAChallengeString() . "=" 
												  . $_GET[$challengeView->GetSeeAChallengeString()] . 
												  "&" . $this->addCommentOnChallengeString . 
												  "=" . $_GET[$challengeView->GetSeeAChallengeString()] . "' method='post'>
				<label>Title</label><br>
				<input name='" . $this->titleString . "' type='text' ></input><br>
				<label>Comment</label><br>
				<textarea name='". $this->commentString . "' rows='4' cols='50'>
				</textarea><br>
				<button class='.button-link' type='submit'>Comment</button>
			</form>		
		";
		
		return $html;
	}
	
	/**
	 * @var Comment
	 * @var User Account 
	 * @var \view\ChalllengeView to get URL strings
	 * @return Start HTML for showwing comment
	 */
	public function WriteCommentHTMLStart($comment, $user, $challengeView, $ourUser){

		$html = "
			<div class='comment' id='" . $comment['ID'] . "'>
				<label>Posted by: <a href='index.php?" . $this->userIDString . "=". $user['ID'] . " '>". $user['FName'] . " " . $user['LName'] . "</a> </label><br>
				<label>Title:</label><br>
				<p> " . $comment['Title'] . " </p>
				<label>Comment:</label><br>
				<p> " . $comment['Comment'] . " </p>";
		// If it´s our comment, we can remove it
		if($ourUser){
			$html .= "<a href='index.php?" . $challengeView->GetSeeAChallengeString() . "=" . $_GET[$challengeView->GetSeeAChallengeString()] . "&"
			. $this->removeCommentString . "=" . $comment['ID'] . "'>Click here to remove your comment.</a>";
		}
				
		$html .= "<hr>";
				
		$html .= "</div>";
		
		return $html;
	}
	
	public function WriteCommentHTMLEnd($comment, $user, $challengeView){
		return "
			<div class='addCommentOnComment'>
						<form action='index.php?". $challengeView->GetSeeAChallengeString() . "=" 
												  . $_GET[$challengeView->GetSeeAChallengeString()] .
												  "&AddCommentToCommentID=". $comment[0] ."' method='post'>
							<label> Do you agree? </label><br>
							<label>Title</label><br>
							<input name='" . $this->titleString . "' type='text' ></input><br>
							<label>Comment</label><br>
							<textarea name='". $this->commentString . "' rows='4' cols='50'>
							</textarea>	<br>
							<button class='.button-link' type='submit'>Comment</button>
						</form>
					</div>
			</div>
			<hr>";
		
	}
	
	/**
	 * @return HTML to show a comment that is on another comment
	 */
	public function WriteCommentOnCommentHTML($comment, $user , $challengeView){
		return "
				<div class='comment' id='" . $comment[0] . "'>
					<label>Posted by: <a href='index.php?userID=". $user[0] . " '>". $user[4] . " " . $user[5] . "</a> </label><br>
					<label>Title:</label><br>
					<p> " . $comment[3] . " </p>
					<label>Comment:</label><br>
					<p> " . $comment[4] . " </p>
				</div>";
	}

	/**
	 * @return String with URL
	 */
	public function GetAddCommentOnCommentString(){
		return $this->addCommentOnCommentString;
	}
	
	/**
	 * @return String with URL
	 */
	public function GetAddCommentOnChallengeString(){
		return $this->addCommentOnChallengeString;
	}
	
	/**
	 * @return String with URL
	 */
	public function GetRemoveACommentString(){
		return $this->removeCommentString;
	}
	
	
	/**
	 * @return String
	 */
	 public function GetTitleString(){
	 	return $this->titleString;
	 }
	 
	 /**
	 * @return String
	 */
	 public function GetCommentString(){
	 	return $this->commentString;
	 }
	 
	  /**
	 * @return String
	 */
	 public function GetUserIDString(){
	 	return $this->userIDString;
	 }
	
}
