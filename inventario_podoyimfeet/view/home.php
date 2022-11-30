<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
		<?php include("main-header.php");?>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
		<?php include("main-sidebar.php");?>
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
		<?php if ($permisos_ver==1){?>
		<section class="content-header">
          <h1>
            Panel de Control
            <small>Version 2.0</small>
          </h1>
          <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Inicio</li>
            
          </ol>
        </section>
		
		
		        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->
         

          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Reporte de ventas <?php echo date('Y');?></h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                      <p class="text-center">
                        <strong>Compras & Ventas <?php echo date('Y');?></strong>
						 
                      </p>
                    <div class="chart">
						<canvas id="barChart" style="height:450px"></canvas>
					</div>
                    </div><!-- /.col -->
                    <div class="col-md-4">
					<!-- Info Boxes Style 2 -->
					  <div class="info-box bg-purple">
						<span class="info-box-icon"><i class="fa fa-tags"></i></span>
						<div class="info-box-content">
						  <span class="info-box-text">Inventario Neto</span>
						  <span class="info-box-number"><?php echo sum_inventory();?></span>
						  <div class="progress">
							<div class="progress-bar" style="width: 100%"></div>
						  </div>
						  <span class="progress-description">
							Productos en stock: <?php echo count_stock();?>
						  </span>
						</div><!-- /.info-box-content -->
					  </div><!-- /.info-box -->
				</div>	
				<div class="col-md-4">
				  <div class="info-box bg-green">
					<span class="info-box-icon"><i class="fa fa-money"></i></span>
					<div class="info-box-content">
					  <span class="info-box-text">Ventas <?php echo date('Y');?></span>
					  <span class="info-box-number"><?php sum_sales();?></span>
					  <div class="progress">
						<div class="progress-bar" style="width: 100%"></div>
					  </div>
					  <span class="progress-description">
						Facturas emitidas: <?php echo count_sales();?>
					  </span>
					</div><!-- /.info-box-content -->
				  </div><!-- /.info-box -->
				</div>
			<div class="col-md-4">
				  <div class="info-box bg-yellow">
					<span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
					<div class="info-box-content">
					  <span class="info-box-text">Compras <?php echo date('Y');?></span>
					  <span class="info-box-number"><?php sum_purchases();?></span>
					  <div class="progress">
						<div class="progress-bar" style="width: 100%"></div>
					  </div>
					  <span class="progress-description">
						Compras realizadas: <?php count_purchases();?>
					  </span>
					</div><!-- /.info-box-content -->
				  </div><!-- /.info-box -->
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- ./box-body -->
                <div class="box-footer">
                  
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <!-- TABLE: LATEST ORDERS -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Últimas ventas</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                        <tr>
                          <th>Factura Nº</th>
                          <th>Cliente</th>
                          <th>Fecha</th>
                          <th class='text-right'>Total	</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
						latest_sales();
						?>
                      </tbody>
                    </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                  <a href="new_sale.php" class="btn btn-sm btn-default btn-flat pull-left">Nueva venta</a>
                  <a href="manage_invoice.php" class="btn btn-sm btn-default btn-flat pull-right">Ver todas las facturas</a>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="col-md-4">
 



              <!-- PRODUCT LIST -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Nuevos productos</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
				<?php 
					recently_products();
				?>
                 
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="products.php" class="uppercase">Ver todos los productos</a>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
		
		
		
		
		
		
		<?php 
		} else{
		?>	
		<section class="content">
			<div class="alert alert-danger">
				<h3>Acceso denegado! </h3>
				<p>No cuentas con los permisos necesario para acceder a este módulo.</p>
			</div>
		</section>		
		<?php
		}
		?>
      </div><!-- /.content-wrapper -->
      <?php include("footer.php");?>
    </div><!-- ./wrapper -->
	<?php //include("js.php");?>
  </body>
</html>

 <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <script>
      $(function () {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //--------------
        //- AREA CHART -
        //--------------
        var areaChartData = {
          labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
          datasets: [
            {
              label: "Compras",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [<?php echo sum_purchases_month(1);?>, <?php echo sum_purchases_month(2);?>, <?php echo sum_purchases_month(3);?>, <?php echo sum_purchases_month(4);?>, <?php echo sum_purchases_month(5);?>, <?php echo sum_purchases_month(6);?>, <?php echo sum_purchases_month(7);?>,<?php echo sum_purchases_month(8);?>,<?php echo sum_purchases_month(9);?>,<?php echo sum_purchases_month(10);?>,<?php echo sum_purchases_month(11);?>,<?php echo sum_purchases_month(12);?>]
            },
            {
              label: "Ventas",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [<?php echo sum_sales_month(1);?>, <?php echo sum_sales_month(2);?>, <?php echo sum_sales_month(3);?>, <?php echo sum_sales_month(4);?>, <?php echo sum_sales_month(5);?>, <?php echo sum_sales_month(6);?>, <?php echo sum_sales_month(7);?>,<?php echo sum_sales_month(8);?>,<?php echo sum_sales_month(9);?>,<?php echo sum_sales_month(10);?>,<?php echo sum_sales_month(11);?>,<?php echo sum_sales_month(12);?>]
            }
          ]
        };
        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        barChartData.datasets[1].fillColor = "#00a65a";
        barChartData.datasets[1].strokeColor = "#00a65a";
        barChartData.datasets[1].pointColor = "#00a65a";
        var barChartOptions = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: true,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - If there is a stroke on each bar
          barShowStroke: true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth: 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing: 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing: 1,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to make the chart responsive
          responsive: true,
          maintainAspectRatio: true
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
      });
    </script>


	

