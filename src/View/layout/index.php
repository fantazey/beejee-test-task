<div class="row justify-content-right">
    <div class="col offset-11 mt-4">
        <?php if ($this->hasUser) { ?>
            <button class="btn btn-primary" onclick="logoutRedirect();">Logout</button>
        <?php } else { ?>
            <button class="btn btn-primary" onclick="loginRedirect();">Login</button>
        <?php } ?>
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
                        <th>Username<a class="ml-2 badge badge-secondary" href="<?=$this->paginator->getHrefToSort('username')?>"><?=$this->paginator->getSortSymbol('username')?></a></th>
                        <th>Email<a class="ml-2 badge badge-secondary" href="<?=$this->paginator->getHrefToSort('email')?>"><?=$this->paginator->getSortSymbol('email')?></a></th>
                        <th>Content</th>
                        <th>State<a class="ml-2 badge badge-secondary" href="<?=$this->paginator->getHrefToSort('state')?>"><?=$this->paginator->getSortSymbol('state')?></a></th>
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
                                <div class="task-content" id="content_<?=$values['id']?>">
                                    <?=$values['content']?>
                                    <?php if ($this->hasUser) {?>
                                        <span class="badge badge-secondary toggle-edit" onclick="toggleEdit(<?=$values['id']?>)">✎</span>
                                    <?php } ?>
                                </div>
                                <div class="edit-form" style="display:none" id="form_<?=$values['id']?>">
                                    <form method="post" action="/?action=edittask">
                                        <div class="form-group">
                                            <textarea class="form-control" name="content" cols="10" rows="3"><?=$values['content']?></textarea>
                                            <input type="hidden" name="id" value="<?=$values['id']?>">
                                            <input type="hidden" name="page" value="<?=$this->paginator->getCurrentPage()?>">
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-info close-task" type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <?php if ($values['isActive']) { ?>
                                    <?php if ($this->hasUser) { ?>
                                        <form method="post" action="/?action=markdone">
                                            <input type="hidden" name="id" value="<?=$values['id']?>">
                                            <input type="hidden" name="page" value="<?=$this->paginator->getCurrentPage()?>">
                                            <button class="btn btn-info close-task">Complete</button>
                                        </form>
                                    <?php } else { ?>
                                        <span class="btn btn-success disabled">Task active</span>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="btn btn-success disabled">Task completed</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if ($this->paginator->getPageCount() > 0) {?>
                <ul class="pagination">
                    <?php foreach ($this->paginator->getPages() as $page) { $pageClass = $page['isActive'] ? 'active' : ''; ?>
                        <li class="page-item <?=$pageClass?>">
                            <a class="page-link" href="<?=$this->paginator->getHrefToPage($page['page']);?>"><?=$page['page'];?></a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </section>
    </div>
</div>
<script>
    function loginRedirect() {
        window.location = '/?action=login';
    }
    function logoutRedirect() {
        window.location = '/?action=logout'
    }
    function toggleEdit(id) {
        const form = document.querySelector('#form_' + id);
        const content = document.querySelector('#content_' + id);
        content.style.display = 'none';
        form.style.display = 'block';
        debugger;
    }
</script>