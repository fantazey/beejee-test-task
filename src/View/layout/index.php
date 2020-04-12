<div class="row justify-content-right">
    <div class="col offset-11 mt-4">
        <button class="btn btn-primary" onclick="loginRedirect();">Login</button>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col col-3">
        <h3 class="row justify-content-center mt-4">
            Create Task
        </h3>
        <?php if (count($this->errors) > 0) { ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($this->errors as $error) { ?>
                    <span><?=$error?></span>
                <?php } ?>
            </div>
        <?php } ?>
        <section class="row justify-content-center">
            <form action="/?action=createtask" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="text" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control"></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </section>
    </div>
    <div class="col col-8">
        <h3 class="row justify-content-center mt-4">
            Tasks
        </h3>
        <section class="row justify-content-center">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Content</th>
                        <th>State</th>
                    </tr>
                    </thead>
                <tbody>
                    <?php foreach ($this->tasks as $task) { $values = $task->getValuesForTemplate() ?>
                        <tr>
                            <td>
                                <span title="<?=$values['username']?>"><?=$values['username']?></span>
                            </td>
                            <td>
                                <span title="<?=$values['email']?>"><?=$values['email']?></span>
                            </td>
                            <td>
                                <?=$values['content']?>
                            </td>
                            <td>
                                <?php if ($values['isActive']) { ?>
                                    <form method="post" action="/?action=markdone">
                                        <input type="hidden" name="id" value="<?=$values['id']?>">
                                        <input type="hidden" name="page" value="<?=$this->paginator->getCurrentPage()?>">
                                        <button class="btn btn-info close-task">Complete</button>
                                    </form>
                                <?php } else { ?>
                                    <span class="btn btn-success disabled">Task completed</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <ul class="pagination">
                <?php foreach ($this->paginator->getPages() as $page) { $pageClass = $page['isActive'] ? 'active' : ''; ?>
                    <li class="page-item <?=$pageClass?>"><a class="page-link" href="?page=<?=$page['page'];?>"><?=$page['page'];?></a></li>
                <?php } ?>
            </ul>
        </section>
    </div>
</div>
<script>
    function loginRedirect() {
        window.location = '/?action=login';
    }
</script>