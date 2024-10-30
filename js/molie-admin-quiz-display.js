jQuery(document).ready(
	function(){
		jQuery(".canvasQuestion[type='multiple_choice_question']")
			.each(
				function(index,value){
					jQuery(value)
						.on("click", function(){	
								feedback = jQuery(this).attr("feedback");
								if(feedback!=""){
									jQuery("#feedback_" + jQuery(this).attr("counter")).html(feedback);
									jQuery("#feedback_" + jQuery(this).attr("counter")).css("color","#F00");
								}else{
									jQuery("#feedback_" + jQuery(this).attr("counter")).html("Correct");
									jQuery("#feedback_" + jQuery(this).attr("counter")).css("color","#0F0");
								}
							}
						)
				}
			)
			
		jQuery("span.saq")
			.each(
				function(index,value){
					jQuery(value)
						.on("click", function(){	
								
								counter = jQuery(this).attr("counter");
								
								answers = jQuery("input.answers[counter='" + jQuery(this).attr("counter") + "']").attr("value").split("|||");								
								answer = jQuery("input.answerbox[counter='" + jQuery(this).attr("counter") + "']").attr("value");
								
								if(answers.indexOf(answer)==-1){
									jQuery("#feedback_" + counter).html(jQuery("p.canvasQuestion[counter='" + jQuery(this).attr("counter") + "']").attr("ffeedback"));
									jQuery("#feedback_" + counter).css("color","#F00");
								}else{
									jQuery("#feedback_" + counter).html(jQuery("p.canvasQuestion[counter='" + jQuery(this).attr("counter") + "']").attr("cfeedback"));
									jQuery("#feedback_" + counter).css("color","#0F0");
								}
							}
						)
				}
			)
			
		jQuery("span.fitbq")
			.each(
				function(index,value){
					jQuery(value)
						.on("click", function(){	
								
								counter = jQuery(this).attr("counter");
								
								jQuery("div.fitbq input.question")
									.each(
										function(index,value){
											answer = jQuery(value).attr("value");
											answers = jQuery("div.fitbq[counter=" + counter + "] input.answers[counter=" + jQuery(value).attr("counter") + "]").attr("value").split("|||");
											if(answers.indexOf(answer)!=-1){
												jQuery(value)
													.css("border", "3px solid #0F0");
											}else{
												jQuery(value)
													.css("border", "3px solid #F00");
											}
										}
									);
								
							}
						)
				}
			)			
	
	}
);