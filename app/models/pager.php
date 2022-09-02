<?php
/*
** Pagination class
*
*/

class Pager {
    public $links       = [];
    public $offset      = 0;
    public $page_num    = 1;
    public $start       = 1;
    public $end         = 1;
    
    public function __construct($limit = 10, $extras = 1) {
        
        $page_num = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $page_num = $page_num < 1 ? 1 : $page_num;

        // creating a starter and end numbers of links to be displayed
        $this->end = $page_num + $extras;
        $this->start = $page_num - $extras;

        if($this->start < 1) {
            $this->start = 1;
        }

        // saving offsets 
        $this->offset = ($page_num - 1) * $limit;
        //echo $this->offset; die;
        $this->page_num = $page_num;

        // Creating links from the url to be used to navigate
        //echo $_SERVER['QUERY_STRING'];

        // replacing url= with ""
        // creating current link, pre link and nxt link
        
        $current_link = URLROOT . '/' . str_replace('url=', "", $_SERVER['QUERY_STRING']);
        $current_link = !strstr($current_link, 'page=') ? $current_link . "&page=1" : $current_link ; 
        $first_link = preg_replace('/page=[0-9]+/', 'page=1', $current_link);
        $next_link = preg_replace('/page=[0-9]+/', 'page='.($page_num + $extras + 1), $current_link);
       
        
        // creating links for previous links
        // if($page_num == 1) {
        //     $prev_link = preg_replace('/page=[0-9]+/', 'page=1', $current_link);
        // } else {
        //     $prev_link = preg_replace('/page=[0-9]+/', 'page='.($page_num - 1), $current_link);
        // }


        // Adding links to onbject links of array
        $this->links['first'] = $first_link;
        //$this->links['prev'] = $prev_link;
        $this->links['current'] = $current_link;
        $this->links['next'] = $next_link;
    }


    public function display() {
        ?>
            <br class="clearfix">
            <div class="d-flex justify-content-center mt-2">
                <nav aria-label="..." class="pt-2">
                    <ul class="pagination" class="">
                        <li class="page-item ">
                            <a class="page-link" href="<?= $this->links['first']; ?>" tabindex="-1">First</a>
                        </li>
                        <!-- <li class="page-item active">
                            <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                        </li> -->

                        <?php for($x = $this->start; $x <= $this->end; $x++): ?>
                        <li class="page-item"> 
                            <a class="page-link <?= $this->page_num == $x ? 'active' : '' ?>" href="
                            <?= preg_replace('/page=[0-9]+/', 'page='.$x, $this->links['current']) ?>
                            "><?= $x ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item">
                            <a class="page-link" href="<?= $this->links['next']; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php
    }


}



?>