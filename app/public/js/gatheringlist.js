// setInterval(() => {
//     console.log("Checking gathering statuses...");

//     fetch('/api/check-gathering-status') // adjust path if needed
//         .then(res => {
//             if (!res.ok) {
//                 throw new Error("Network response was not ok " + res.statusText);
//             }
//             return res.json();
//         })
//         .then(data => {
//             console.log("Server Response:", data);

//             if (data.updated) {
//                 console.log("Gatherings updated. Reloading page...");
//                 location.reload(); // Optional: refresh to see changes
//             } else {
//                 console.log("No gatherings updated.");
//             }
//         })
//         .catch(error => console.error("AJAX Error:", error));
// }, 30000); // Every 30 seconds
