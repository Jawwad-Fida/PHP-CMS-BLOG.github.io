//jquery function to check if document has loaded
$(document).ready(function(){

    // CK-EDITOR(v5) code - target id="editor"
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
        console.error( error );
    } );

    //-----------------------------------------




    //------------CHECKBOX (RADIO BUTTONS)----------

    //by clicking top checkbox, rest of the checkboxes will be automatically clicked (checked)

    //target => id="selectAllBoxes" and class="checkBoxes"

    //target the id, if the checkbox has been clicked
    $('#selectAllBoxes').click(function(event){

        //check if the box has been checked(clicked)
        //this->refers to id here
        if(this.checked) {

            //grab the class object, and iterate through the object using .each
            $('.checkBoxes').each(function(){

                //this->refers to class object here
                this.checked = true;

            });

        } else {
            //if the box has not been clicked(checked)

            $('.checkBoxes').each(function(){

                this.checked = false;

            });

        }

    });

    //--------------------------------------------



    //----------LOADER (loading screen)---------

    //declare variable in js = using var

    //load-screen = background
    //loading = loader gif

    var div_box = "<div id='load-screen'><div id='loading'></div></div>";

    //Target the body using jquery
    $("body").prepend(div_box);

    //Target id
    //delay for 700ms 
    $('#load-screen').delay(700).fadeOut(600, function(){
        $(this).remove(); //then remove the gif
    });

    //--------------------------------------------


    //----------User Online (using ajax)------------
    function loadUsersOnline() {

        //sending the ajax request ($_GET) to functions.php -  with a parameter
        $.get("functions.php?onlineusers=result", function(data){ 
            //function we want to execute when we get response from server
            //data variable gets the response

            //targeting container with class="usersonline" (admin_navigation.php)
            $(".usersonline").text(data);

        });//so in this part we sent information 

    }

    //but we need to constantly fetch information from database (without refreshing)

    //so we execute the function every couple of seconds 
    setInterval(function(){

        loadUsersOnline(); //target function (above)

    },500); //every 500ms = 0.5s

    //-----------------------------------------
    
    
    
    




});






