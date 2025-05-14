function toggleSidebar() {
    let sidebar = document.querySelector(".sidebar");
    let mainContent = document.querySelector(".main-content");
    
    sidebar.classList.toggle("collapsed");

    if (sidebar.classList.contains("collapsed")) {
        mainContent.style.marginLeft = "100px";
        mainContent.style.width = "calc(100% - 100px)";
    } else {
        mainContent.style.marginLeft = "250px";
        mainContent.style.width = "calc(100% - 250px)";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    fetchNotifications();

    // Toggle dropdown on button click
    document.getElementById("notification-btn").addEventListener("click", function () {
        document.getElementById("notification-dropdown").classList.toggle("show");
    });
});

function fetchNotifications() {
    fetch('fetch_notifications.php')
        .then(response => response.json())
        .then(data => {
            let notificationList = document.getElementById("notification-list");
            notificationList.innerHTML = ""; // Clear old notifications

            if (data.length === 0) {
                notificationList.innerHTML = "<li>No new notifications</li>";
            } else {
                data.forEach(notification => {
                    let listItem = document.createElement("li");
                    listItem.textContent = notification.message;
                    notificationList.appendChild(listItem);
                });
            }
        })
        .catch(error => console.error('Error fetching notifications:', error));
}

document.addEventListener("DOMContentLoaded", function() {
    fetchDashboardData();
});

function fetchDashboardData() {
    fetch('fetch_dash_data.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById("course-count").textContent = data.enrolled_courses + " Active Courses";
            document.getElementById("progress-percent").textContent = data.avg_progress + "% Average Completion";
            document.getElementById("certificates-earned").textContent = data.certificates_earned + " Certifications Earned";

            // Update Chart Data
            updateProgressChart(data.avg_progress);
        })
        .catch(error => console.error('Error fetching dashboard data:', error));
}

function updateProgressChart(progress) {
    let ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Remaining'],
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        }
    });
}
