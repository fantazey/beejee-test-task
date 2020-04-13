<div class="row justify-content-right">
    <div class="col offset-11 mt-4">
        <button class="btn btn-primary" onclick="indexRedirect();">Tasks</button>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col col-3">
        <?php if (count($this->errors) > 0) { ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($this->errors as $error) { ?>
                    <span><?=$error?></span>
                <?php } ?>
            </div>
        <?php } ?>
        <section class="row justify-content-center">
            <form action="/?action=auth" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </section>
    </div>
</div>
<script>
    function indexRedirect() {
        window.location = '/?action=index';
    }
</script>