<div class="card">
    <div class="card-body">
        @role($showGymRevenueRole)
            <p> Total Gym Revenue = {{$totalGymRevenue}} $</p>
        @endrole
        @role($showCityRevenueRole)
            <p> Total City Revenue = {{$totalCityRevenue}} $</p>
        @endrole
        @role($showTotalRevenueRole)
            <p> Total Revenue = {{$totalRevenue}} $</p>
        @endrole
    </div>
</div>
