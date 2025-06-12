 const ctx = document.getElementById('leaveRatesChart').getContext('2d');

  if (typeof leaveRatesData !== 'undefined' && Object.keys(leaveRatesData).length > 0) {
    const labels = Object.keys(leaveRatesData).map(label => {
      return label.charAt(0).toUpperCase() + label.slice(1).toLowerCase();
    });
    
    const values = Object.values(leaveRatesData);  

    const leaveRatesChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Leave Count',
          data: values,
          backgroundColor: '#24796d',
          borderColor: 'rgba(25, 135, 84, 1)',
          borderWidth: 1
        }]
      },
      options: {
        indexAxis: 'y', // horizontal bars
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            beginAtZero: true,
            ticks: {
              color: '#17524e'
            },
            grid: {
              color: 'rgba(0,0,0,0.1)'
            }
          },
          y: {
            ticks: {
              color: '#17524e'
            },
            grid: {
              color: 'rgba(0,0,0,0.1)'
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `${context.parsed.x} leave(s)`;
              }
            }
          }
        }
      }
    });
  } else {
    console.warn("No data available for Leave Rates Per Department chart.");
  }


const ctxTrend = document.getElementById('leaveTrendChart').getContext('2d');

const trendLabels = Object.keys(leaveTrendData);
const trendValues = Object.values(leaveTrendData);

const leaveTrendChart = new Chart(ctxTrend, {
  type: 'line',
  data: {
    labels: trendLabels,
    datasets: [{
      label: 'Leave Requests',
      data: trendValues,
      fill: true,
      backgroundColor: 'rgba(0, 120, 111, 0.7)', // Dark green fill
      borderColor: '#1b5e20', // Darker green line
      borderWidth: 3,
      tension: 0.4, // Slightly more curved line
      pointBackgroundColor: '#17524e', // Light green dots
      pointBorderColor: '#ffffff',
      pointHoverRadius: 7,
      pointRadius: 5,
      pointHoverBackgroundColor: '#ffffff'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false, 
      },
      title: {
        display: false, 
      }
    },
    scales: {
      x: {        // months label on bottom
        ticks: {
          color: '#17524e',
          font : {
            weight: 'bold'
          }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.09)' 
        }
      },
      y: {       // number labels on left side
        beginAtZero: true,  
        ticks: {
          color: '#17524e',
            font : {
              weight: 'bold'
            }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)' // Subtle grid
        }
      }
    }
  }
});