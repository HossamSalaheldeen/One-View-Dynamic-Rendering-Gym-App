let formRules = () => {
    return {
        rules: {},
    }
};

$(function () {

    $('#year-selector').select2({
        language: {
            noResults: function () {
                return 'No years found';
            },
        },
        placeholder: $('#year-selector').attr("data-placeholder"),
        allowClear: !$('#year-selector').prop("multiple"),
        ajax: {
            url: `/api/years`,
            dataType: "json",
            processResults: function (data) {
                return {
                    results: mapData(data),
                };
            },
        },
    });

    let revenueChart;
    if (revenue.data) {
        revenueChart = drawRevenueChart();
    }
    let genderChart;
    if (gender.data) {
        genderChart = drawGenderChart();
    }

    let citesChart;
    if (cities.data) {
        citesChart = drawCitiesChart();
    }

    let usersChart;
    if (Math.max(...users.data) !== 0) {
        usersChart = drawUsersChart();
    }

    $('#year-selector').on("select2:select", async function (e) {

        revenueChart.destroy();
        genderChart.destroy();
        citesChart.destroy();
        usersChart.destroy();
        await getStatistics($(this).select2('data')[0]['text']).done((res) => {
            $(`#scripts-container`).html(res);

            if (revenue.data) {
                revenueChart = drawRevenueChart();
            }

            if (gender.data) {
                console.log("dsdk", gender.data);
                genderChart = drawGenderChart();
            }

            if (cities.data) {
                citesChart = drawCitiesChart();
            }

            if (Math.max(...users.data) !== 0) {
                usersChart = drawUsersChart();
            }

        });
    });
});

const drawRevenueChart = () => {
    return new Chart(document.getElementById('revenue-chart').getContext('2d'), {
        type: 'line',
        data: {
            labels: revenue.label,
            datasets: [{
                label: 'Revenue',
                data: revenue.data,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            maintainAspectRatio: false,
        }
    });
};

const drawGenderChart = () => {
    return new Chart(document.getElementById('gender-chart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: gender.label,
            datasets: [{
                label: 'Gender',
                data: gender.data,
                backgroundColor: gender.label.map(elem => '#' + Math.floor(Math.random() * 16777215).toString(16)),
                hoverOffset: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
        }
    });
};

const drawCitiesChart = () => {
    return new Chart(document.getElementById('cities-chart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: cities.label,
            datasets: [{
                label: 'Cities',
                data: cities.data,
                backgroundColor: gender.label.map(elem => '#' + Math.floor(Math.random() * 16777215).toString(16)),
                hoverOffset: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
        }
    });
};

const drawUsersChart = () => {
    return new Chart(document.getElementById('users-chart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: users.label,
            datasets: [{
                label: 'Users',
                data: users.data,
                backgroundColor: gender.label.map(elem => '#' + Math.floor(Math.random() * 16777215).toString(16)),
                hoverOffset: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
        }
    });
};


function mapData(data) {
    var data2 = [];
    data.data.forEach(function (item) {
        data2.push({
            id: item.id,
            text: item.name,
        });
    });
    return data2;
}


function getStatistics(year) {
    return $.ajax({
        url: `dashboard?year=${year}`,
        type: 'GET',
        cache: false,
    });
}

