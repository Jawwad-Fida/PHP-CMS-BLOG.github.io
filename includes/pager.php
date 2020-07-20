
<!------------- PAGINATION CONTINUE------------->
<ul class="pager">

    <?php
    
    //loop to display page numbers
    
    for($i=1; $i<=$total_posts_per_page; $i++){ //i loops through page numbers, i -> page number
        //send a GET request -> to limit how many posts we will have per page

        //this link also creates the pages we can navigate

        if($i == $page_number){
            //if the visitor is on current page, then highlight the page he is in
            echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
        }
        else{
            //leave the other pages as it is
            echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
        }
    }
    
    
    if($total_posts_per_page>1){
        //since we are on page 2 or greater, go back previous page
        $previous_page = $total_posts_per_page-1;
        echo "<li class='previous'><a href='index.php?page={$previous_page}'>&larr; Previous</a></li>";
    }


    if($i>$page_number){
        //if value of i (page number) is greater than current page number, then go to the page with greater number(i)
        echo "<li class='next'><a href='index.php?page={$total_posts_per_page}'>&rarr; Next</a></li>";
    }

    ?>

</ul>