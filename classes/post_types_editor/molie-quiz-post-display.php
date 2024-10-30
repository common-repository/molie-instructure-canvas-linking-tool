<?PHP

	class MOLIEquizPostDisplay{
	
		function __construct(){
			add_filter("the_content", array($this, "display"));
			add_action("wp_enqueue_scripts", array($this, "admin_scripts_and_styles"));
		}
	
		function admin_scripts_and_styles(){
			wp_enqueue_script( 'molie-admin-quiz-display', plugins_url() . '/molie-instructure-canvas-linking-tool/js/molie-admin-quiz-display.js', array( 'jquery' ) );
			wp_enqueue_style( 'molie-admin-quiz-display-css', plugins_url() . '/molie-instructure-canvas-linking-tool/css/molie-admin-quiz-display.css' );
		}
		
		function display($content){
		
			global $post;
			
			if($post->post_type=="linkedcanvasquiz"){
				
				global $wpdb;
				$questions = $wpdb->get_results("select * from " . $wpdb->prefix . "postmeta where meta_key like '%canvasQuizQuestion_%' and post_id = " . $post->ID);
				$q_counter = 1;
							
				if(count($questions)!=0){
					
					foreach($questions as $data){
					
						$sharedAnswers = Array();
						
						$post = get_post($data->meta_value);
				
						if($post->canvasQuestion_type!="fill_in_multiple_blanks_question"){
							echo $post->post_content;
						}
		
						$counter = 1;
						
						$questionShown = false;
						
						while(get_post_meta($post->ID, "qa_id_" . $counter, true)!=""){
						
							if($post->canvasQuestion_type=="multiple_choice_question"){
								
								?>
								<p type="<?PHP echo $post->canvasQuestion_type; ?>" class="canvasQuestion" counter="<?PHP echo $q_counter; ?>" weight="<?PHP echo addslashes(get_post_meta($post->ID, "qa_weight_" . $counter, true)); ?>" feedback="<?PHP echo addslashes(str_replace('"',"'",get_post_meta($post->ID, "qa_feedback_" . $counter, true))); ?>"><?PHP echo get_post_meta($post->ID, "qa_answer_" . $counter, true); ?></p>
								<?PHP
								$counter++;
							
							}
							
							if($post->canvasQuestion_type=="short_answer_question"){
								if(!$questionShown){
									?><p type="<?PHP echo $post->canvasQuestion_type; ?>" class="canvasQuestion" counter="<?PHP echo $q_counter; ?>" weight="<?PHP echo addslashes(get_post_meta($post->ID, "qa_weight_" . $counter, true)); ?>" cfeedback="<?PHP echo addcslashes(str_replace('"',"'",get_post_meta($post->ID, "qa_correct_feedback_" . $counter, true)),"\"'"); ?>" ffeedback="<?PHP echo addcslashes(str_replace('"',"'",get_post_meta($post->ID, "qa_incorrect_feedback_" . $counter, true)),"\"'"); ?>">
										<input class="answerbox" type="<?PHP echo $post->canvasQuestion_type; ?>" counter="<?PHP echo $q_counter; ?>" type="text" />
									</p><?PHP
									$questionShown = true;
								}
								array_push($sharedAnswers, get_post_meta($post->ID, "qa_answer_" . $counter, true));
								
								$counter++;
							
							}
							
							if($post->canvasQuestion_type=="fill_in_multiple_blanks_question"){
								
								if(!isset($sharedAnswers[get_post_meta($post->ID, "qa_blankID_" . $counter, true)])){
									$sharedAnswers[get_post_meta($post->ID, "qa_blankID_" . $counter, true)] = array();
								}
								
								array_push($sharedAnswers[get_post_meta($post->ID, "qa_blankID_" . $counter, true)], get_post_meta($post->ID, "qa_answer_" . $counter, true));
								
								$counter++;
							
							}
							
						}
						
						if($post->canvasQuestion_type=="short_answer_question"){
							echo "<input class='answers' counter='" . $q_counter . "' type='hidden' value='" . implode("|||", $sharedAnswers) . "' />";
							?><span class='saq' counter='<?PHP echo $q_counter; ?>'><?PHP echo __("Click to check"); ?></span><?PHP
							?><p id='feedback_<?PHP echo $q_counter; ?>'></p><?PHP
						}
						
						if($post->canvasQuestion_type=="multiple_choice_question"){
							if($counter!=1){
							?><span><?PHP echo __("Click on the right answer"); ?></span><?PHP
							}
							?><p id='feedback_<?PHP echo $q_counter; ?>'></p><?PHP
						}
						
						if($post->canvasQuestion_type=="fill_in_multiple_blanks_question"){
							$newcounter = 1;
							$content = $post->post_content;
							$answercontent = "";
							foreach($sharedAnswers as $index => $answers){
								$content = str_replace("[" . $index . "]", "<input class='question' type='text' length=20 counter='" . $newcounter . "' />", $content);
								$answercontent .= "<input class='answers' type='hidden' counter='" . $newcounter . "' value='" . implode("|||", $answers) . "'/>";
								$newcounter++;
							}
							echo "<div class='fitbq' counter='" . $q_counter . "'>" . $answercontent . $content . "</div>";	
							?><span class='fitbq' counter='<?PHP echo $q_counter; ?>'><?PHP echo __("Click to check"); ?></span><?PHP
						}
						
						$q_counter++;
						
					}
				
				}else{
			
					return $content;
			
				}
				
			}else{
			
				return $content;
			
			}	
			
		}
		
	}
	
	$MOLIEquizPostDisplay = new MOLIEquizPostDisplay();
	