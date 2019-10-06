<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="zmdi zmdi-money-box zmdi-hc-fw"></i> Emitir <small>Recibo</small></h1>
    </div>
<!--    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
--></div>

<div class="container-fluid">

    <div class="row">
        <h3 class="tile-titles text-center font-weight-bold"><b>Emitir recibo por Persona</b></h3>
        <div class="col-xs-12 col-sm-12">
            <div class="col-xs-12 col-md-4 form-group">
                <input class="form-control" type="search" name="nameUser">
            </div>
            <div class="col-xs-12 col-md-4 form-group ">
                <button class="btn btn-success btn-raised btn-xs" name="nameUser">buscar</button>
            </div>

        </div>

        <div class="col-xs-12"
        <div class="tab-pane fade" id="list">
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Cod Suministro</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Direccion</th>
                        <th class="text-center">Monto</th>
                        <th class="text-center">Generar recibo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>564</td>
                        <td>Carlos Alfaro</td>
                        <td>AV. Ku 256</td>
                        <td>s/ 6.25</td>
                        <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Recibo</a></td>


                    </tr>
                    <tr>
                        <td>2</td>
                        <td>231</td>
                        <td>Claudia Rodriguez</td>
                        <td>AV. Bf 256</td>
                        <td>s/ 4.5</td>
                        <td><a href="#!" class="btn btn-info btn-raised btn-xs">G. Recibo</a></td>

                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    </div>

    <div class="row">
        <diV class="row justify-content-center page-header">
            <h3 class="tile-titles text-center font-weight-bold"><b>Emitir recibo por CALLE</b></h3>
        </diV>

        <div class="col-xs-12 col-sm-12">
            <div class="col-xs-12 col-md-4 form-group">
                <input class="form-control" type="search" name="nameUser">
            </div>
            <div class="col-xs-12 col-md-4 form-group ">
                <button class="btn btn-success btn-raised btn-xs" name="nameUser">buscar</button>
            </div>
        </div>


        <div class="col-xs-12">

            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li><a href="#list" data-toggle="tab">Lista</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="new">
                    <div class="container-fluid">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12"
            <div class="tab-pane fade" id="list">
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Calle descripción</th>
                            <th class="text-center">Generar recibo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>AV. Ku 256</td>
                            <td><a href="#!" class="btn btn-success btn-raised btn-xs">Generar Recibo</a></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>AV. Bf 256</td>
                            <td><a href="#!" class="btn btn-success btn-raised btn-xs">Generar Recibo</a></td>

                        </tr>
                        <tr>
                            <td>3</td>
                            <td>AV. FT 123</td>
                            <td><a href="#!" class="btn btn-success btn-raised btn-xs">Generar Recibo</a></td>

                        </tr>

                        </tbody>
                    </table>
                    <ul class="pagination pagination-sm">
                        <li class="disabled"><a href="#!">«</a></li>
                        <li class="active"><a href="#!">1</a></li>
                        <li><a href="#!">2</a></li>
                        <li><a href="#!">3</a></li>
                        <li><a href="#!">4</a></li>
                        <li><a href="#!">5</a></li>
                        <li><a href="#!">»</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>