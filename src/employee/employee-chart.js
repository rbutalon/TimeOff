const ctx = document.getElementById('leaveBalanceChart').getContext('2d');

const labels = Object.keys(leaveBalanceData);
const values = Object.values(leaveBalanceData);

const leaveBalanceChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: labels,
    datasets: [{
      data: values,
      backgroundColor: ['#3fd7c0', '#1c8e83', '#11403a', '#70c1b3', '#24796d', '#0b403a'],
      borderWidth: 1
    }]
  },
  options: {
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          color: '#17524e',
          font: {
            size: 12,
            weight: 'bold'
          }
        }
      },
      title: {
        display: false
      }
    }
  }
});
