const calendarGrid = document.getElementById('calendarGrid');
const currentMonthElement = document.getElementById('currentMonth');
const prevMonthButton = document.getElementById('prevMonth');
const nextMonthButton = document.getElementById('nextMonth');
const todayButton = document.getElementById('todayButton'); 
const leaveLegendElement = document.getElementById('leaveLegend');

let currentDate = new Date();

const employeeLeaveData = typeof approvedLeavesData !== "undefined" ? approvedLeavesData : [];
console.log(employeeLeaveData);

function generateCalendar(year, month, leaveRanges) {
  calendarGrid.innerHTML = '';
  currentMonthElement.textContent = new Date(year, month).toLocaleString('default', { month: 'long', year: 'numeric' });

  const firstDayOfMonth = new Date(year, month, 1);
  const lastDayOfMonth = new Date(year, month + 1, 0);
  const daysInMonth = lastDayOfMonth.getDate();
  const firstDayOfWeek = firstDayOfMonth.getDay();

  // Add empty cells for the days before the first day of the month
  for (let i = 0; i < firstDayOfWeek; i++) {
    const emptyCell = document.createElement('div');
    emptyCell.classList.add('calendar__day', 'calendar__day--inactive');
    calendarGrid.appendChild(emptyCell);
  }

  // Add the days of the month
  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(year, month, day);
    const dayCell = document.createElement('div');
    dayCell.classList.add('calendar__day');
    dayCell.textContent = day;

    // Highlight today's date
    const today = new Date();
    if (date.toDateString() === today.toDateString()) {
      dayCell.classList.add('calendar__day--today');
    }

    // Highlight leave ranges
    if (leaveRanges) {
      leaveRanges.forEach(range => {
        const startDateParts = range.start.split('-');
        const endDateParts = range.end.split('-');

        const startDate = new Date(
            parseInt(startDateParts[0]),
            parseInt(startDateParts[1]) - 1,
            parseInt(startDateParts[2])
        );

        const endDate = new Date(
            parseInt(endDateParts[0]),
            parseInt(endDateParts[1]) - 1,
            parseInt(endDateParts[2])
        );

        console.log("Current Date:", date);
        console.log("Start Date:", startDate);
        console.log("End Date:", endDate);

        if (date >= startDate && date <= endDate) {
          const leaveTypeClass = `calendar__day--${range.type.toLowerCase().replace(/\s+/g, '-')}`;
          dayCell.classList.add(leaveTypeClass);
        }
      });
    }

    calendarGrid.appendChild(dayCell);
  }
}

function navigateMonth(offset) {
  currentDate.setMonth(currentDate.getMonth() + offset);
  generateCalendar(currentDate.getFullYear(), currentDate.getMonth(), employeeLeaveData);
}

function goToToday() {
  currentDate = new Date();
  generateCalendar(currentDate.getFullYear(), currentDate.getMonth(), employeeLeaveData);
}

prevMonthButton.addEventListener('click', () => navigateMonth(-1));
nextMonthButton.addEventListener('click', () => navigateMonth(1));
todayButton.addEventListener('click', goToToday); 


const leaveTypes = [
  { type: 'Earned Leave', color: 'var(--clr-earned-leave)' },
  { type: 'Sick Leave', color: 'var(--clr-sick-leave)' },
  { type: 'Emergency Leave', color: 'var(--clr-emergency-leave)' },
  { type: 'Maternity Leave', color: 'var(--clr-maternity-leave)' },
  { type: 'Paternity Leave', color: 'var(--clr-paternity-leave)' },
  { type: 'Casual Leave', color: 'var(--clr-casual-leave)' }
];

function generateLegend() {
  leaveTypes.forEach(leave => {
    const listItem = document.createElement('li');
    listItem.classList.add('legend__item');

    const colorSwatch = document.createElement('span');
    colorSwatch.classList.add('legend__color', `legend__color--${leave.type.toLowerCase().replace(/\s+/g, '-')}`);
    colorSwatch.style.backgroundColor = leave.color;

    const label = document.createElement('span');
    label.textContent = leave.type;

    listItem.appendChild(colorSwatch);
    listItem.appendChild(label);
    leaveLegendElement.appendChild(listItem);
  });
}

generateCalendar(currentDate.getFullYear(), currentDate.getMonth(), employeeLeaveData);
generateLegend();