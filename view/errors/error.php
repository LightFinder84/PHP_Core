<?php
    $exception = $this->data["exception"];
    $this->title = $exception->getMessage();
?>

<div style="width: 100%; height: 500px; background-color:darkgray; padding: 100px;">
    <h1>
        <?php echo $exception->getCode()." - ".$exception->getMessage() ?> 
    </h1>
</div>