<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">


      <nav class="main-header navbar navbar-expand">
        <ul class="navbar-nav ml-auto d-flex align-items-center" style="gap: 8px;">
          <form action="<?php echo URL_BASE ?>dashboard" method="post" class="d-flex align-items-center" style="gap: 8px;">
            <input type="date" value="<?php echo $datas['inicio'] ?>" name="inicio" class="form-control form-control-sm" required>
            <input type="date" value="<?php echo $datas['fim'] ?>" name="fim" class="form-control form-control-sm" required>
            <button type="submit" class="btn btn-primary btn-sm">Carregar</button>
          </form>
        </ul>
      </nav>

      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?php echo URL_BASE ?>assets/#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?php echo URL_BASE ?>assets/#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo URL_BASE ?>assets/#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="<?php echo URL_BASE ?>assets/#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>


        </div>
      </div>
    </div>
  </section>
</div>