window.onload = function () {
	
	let fillColor = 'rgba(255, 255, 255, 1)';
	let backgroundColor = 'rgba(155, 89, 182,0.5)';
	let pointColor = 'rgba(220,180,0,1)';
	let fontColor = '#808080';

	function themeSelect() {
		let theme = localStorage.getItem('theme');
		
		if(typeof theme !== 'undefined') {
			if(theme == 'white') {
				fillColor = 'rgba(255, 255, 255, 1)';
				backgroundColor = 'rgba(155, 89, 182,0.5)';
				pointColor = 'rgba(220, 180, 0, 1)';
				fontColor  = '#808080';
			}
			else {
				fillColor = 'rgba(255, 255, 255, 1)';
				backgroundColor = 'rgba(155, 89, 182,0.5)';
				pointColor = 'rgba(220, 180, 0, 1)';
				fontColor  = '#fff';
			}
		}
		return true;
	}

	themeSelect();

	var monthlySalesData = {  
		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],  
		datasets: [
			{  
				label: "USD",  
				fillColor: fillColor,  
				backgroundColor: backgroundColor,  
				pointColor: pointColor,  
				data: [20, 30, 80, 20, 40, 10, 60, 55, 30, 40, 65, 14]  
			}
		]  
	};  

	// Chart option | Here we can customize the chart. please refer to the documentation at chart js site.  
	var settings = {  
		responsive: true,  
		animation:{  
			duration:4000,  
			easing:'easeOutElastic'  
		},
		legend: {
			labels: {
				fontColor: fontColor
			}
		},
		// title: {  
		// 	display: true,  
		// 	text: 'Monthly Sales',
		// 	fontStyle:'bold',  
		// 	fontFamily:'Nunito',  
		// 	fontSize:25,
		// 	color: '#fff',
		// },  
		scales: {  
			yAxes: [{  
				display: true,  
				ticks: {  
					max: 100,    // minimum will be 0, unless there is a lower value.  
					beginAtZero: true,   // minimum value will be 0.  
					stepSize:10,
					fontColor: fontColor,
					// fontSize: 18,
				}  
			}],  
			xAxes: [{  
				display: true,  
				ticks: {
					fontColor: fontColor,
				}  
			}],  
		}  
	}; 

	var monthlySales = document.getElementById("monthlySales");  
    var myLineChart = new Chart(monthlySales, {  
        type: 'line',  
        data: monthlySalesData,  
        options: settings  
    });
	

	var twoProjectData = {  
		labels: ['Data 1', 'Data 2', 'Data 3', 'Data 4', 'Data 5', 'Data 6', 'Data 7'],  
		datasets: [
			{  
				label: "Year 2016",  
				fillColor: fillColor,  
				backgroundColor: backgroundColor,  
				pointColor: pointColor,  
				data: [20, 30, 80, 20, 40, 10, 60]  
			},   
			{  
				label: "Year 2017",  
				fillColor: fillColor,  
				backgroundColor: backgroundColor,  
				pointColor: pointColor,   
				data: [60, 10, 40, 30, 80, 30, 20]  
			}
		]  
	};  

	// Chart option | Here we can customize the chart. please refer to the documentation at chart js site.  
	var twoProjectDataSetting = {  
		responsive: true,  
		animation:{  
			duration:4000,  
			easing:'easeOutElastic'  
		},
		legend: {
			labels: {
				fontColor: fontColor
			}
		},
		scales: {  
			yAxes: [{  
				display: true,  
				ticks: {  
					max: 100,    // minimum will be 0, unless there is a lower value.  
					beginAtZero: true,   // minimum value will be 0.  
					stepSize:10,
					fontColor: fontColor,
				}  
			}],  
			xAxes: [{  
				display: true,  
				ticks: {
					fontColor: fontColor,
				}  
			}],  
		}  
	}; 

	var twoProject = document.getElementById("twoProject");  
    new Chart(twoProject, {  
        type: 'line',  
        data: twoProjectData,  
        options: twoProjectDataSetting  
    }); 



	// Pie Chart
	var canvas = document.getElementById("pieChart");
	var ctx = canvas.getContext('2d');

	var data = {
		labels: ["Rice ", "Fish", 'Meat', 'Vegitable', 'Fast Food'],
		datasets: [
			{
				fill: true,
				backgroundColor: ['green', 'blue', 'red', 'orange', 'black'],
				data: [10, 15, 20, 30, 25],
				borderColor: ['green', 'blue', 'red', 'orange', 'black'],
				borderWidth: [2, 2, 2, 2, 2]
			}
		]
	};
	// Notice the rotation from the documentation.

	var options = { 
		responsive: true,
		legend: {
			labels: {
				fontColor: fontColor
			}
		},
		animation:{  
			duration:4000,  
			easing:'easeOutElastic'  
		}, 
		// title: {  
		// 	display: true,  
		// 	text: 'All products sales details',
		// 	fontStyle:'bold',  
		// 	fontFamily:'Nunito',  
		// 	fontSize:25 ,
		// 	position: 'top'
		// },  
	};

	// Chart declaration:
	var pieChart = new Chart(ctx, {
		type: 'pie',
		data: data,
		options: options
	});
}
