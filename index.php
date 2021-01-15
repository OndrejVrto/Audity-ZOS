<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";
    
    $page = new \Page\Page();

ob_start();  // Začiatok definície hlavného obsahu
?>
    <div class="card">
        <div class="card-body p-0">

            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            P.č.
                        </th>
                        <th style="width: 20%">
                            Audit
                        </th>
                        <th style="width: 20%">
                            Zodpovedný
                        </th>
                        <th>
                            Progres
                        </th>
                        <th style="width: 8%" class="text-center">
                            Stav
                        </th>
                        <th style="width: 30%">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            1.
                        </td>
                        <td>
                            <a>
                                ISO 9001
                            </a>
                            <br/>
                            <small>
                                Dňa 01.10.2019
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar2.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar3.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar04.png">
                                </li>
                            </ul>
                        </td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-red" role="progressbar" style="width: 34%">
                                </div>
                            </div>
                            <small>
                                Spracovaný na 34%
                            </small>
                        </td>
                        <td class="project-state">
                            <span class="badge badge-danger">Nekompletný</span>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="fas fa-folder">
                                </i>
                                Detaily
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Editovať
                            </a>
                            <a class="btn btn-danger btn-sm" href="#">
                                <i class="fas fa-trash">
                                </i>
                                Vymazať
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            2.
                        </td>
                        <td>
                            <a>
                                ISO 14001
                            </a>
                            <br/>
                            <small>
                                Dňa 01.10.2019
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar3.png">
                                </li>
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar.png">
                                </li>
                            </ul>
                        </td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-orange" role="progressbar" style="width: 84%">
                                </div>
                            </div>
                            <small>
                                Spracovaný na 84%
                            </small>
                        </td>
                        <td class="project-state">
                            <span class="badge badge-warning">Skoro hotový</span>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="fas fa-folder">
                                </i>
                                Detaily
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Editovať
                            </a>
                            <a class="btn btn-danger btn-sm" href="#">
                                <i class="fas fa-trash">
                                </i>
                                Vymazať
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            3.
                        </td>
                        <td>
                            <a>
                                Interný
                            </a>
                            <br/>
                            <small>
                                Dňa 01.06.2019
                            </small>
                        </td>
                        <td>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <img alt="Avatar" class="table-avatar" src="/dist/img/avatar2.png">
                                </li>
                            </ul>
                        </td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-green" role="progressbar" style="width: 100%">
                                </div>
                            </div>
                            <small>
                                Spracovaný na 100%
                            </small>
                        </td>
                        <td class="project-state">
                            <span class="badge badge-success">Kompletný</span>
                        </td>
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="#">
                                <i class="fas fa-folder">
                                </i>
                                Detaily
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Editovať
                            </a>
                            <a class="btn btn-danger btn-sm" href="#">
                                <i class="fas fa-trash">
                                </i>
                                Vymazať
                            </a>
                        </td>
                    </tr>
                    
                </tbody>
            </table>

        </div>
    </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky