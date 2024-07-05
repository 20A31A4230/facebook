<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Main Page</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="./assets/axios.min.js"></script> -->
    <script src="./assets/axios.js"></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['user']['email'])) {
        header("Location: /facebook");
        exit();
    }

    $email = $_SESSION['user']['email'];
    $id = $_SESSION['user']['id'];
    $name = $_SESSION['user']['name'];
    $image = $_SESSION['user']['image'];

    echo "<script> var id = $id; </script>";
    echo "<script> var email = '$email'; </script>";
    echo "<script> var name = '$name'; </script>";
    echo "<script> var image = '$image'; </script>";
    ?>
    <nav class="navbar bg-body-tertiary">
        <div class="container">
            <div class="col-3">
                <a class="navbar-brand">Welcome to Facebook, <b><?= $name ?></b></a>
            </div>
            <div class="col-3 text-center">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProfileModal" onclick="fetchUserDetails()">Edit Profile</button>
            </div>
            <div class="col-3 text-end">
                <button class="btn btn-danger" onclick="logout()">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Friends List Section -->
        <h2>Friends List</h2>
        <div id="friendsList" class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Friend Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="friendsListTableBody"> </tbody>
            </table>
        </div>

        <!-- Pending Requests Section -->
        <h2>Pending Requests</h2>

        <div id="pendingRequests" class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Friend Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="pendingRequestsTableBody"> </tbody>
            </table>
        </div>


        <!-- acceptRequestsTableBody -->
<h2>Accept Requests</h2>

<div id="acceptRequests" class="row">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Friend Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="acceptRequestsTableBody"> </tbody>
    </table>
</div>


        <!-- Recommendations Section -->
        <!-- recommendationsList -->
        <h2>Recommendations</h2>
        <div id="recommendations" class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Friend Name</th>
                        <th scope="col">Profile Pic</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="recommendationsList"> </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" id="close" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editProfilePic" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="editProfilePic" name="profile_pic">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveProfile()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            loadFriendsList();
            loadPendingRequests();
            loadAcceptRequests();
            loadRecommendations();
        });

        async function logout() {
            const res = await axios.get('/facebook/api/logout.php');
            if (res.data.status == 'success') {
                alert('Logged out successfully!');
                window.location.replace('/facebook');
            } else {
                alert('Logout failed!');
            }
        }

        async function fetchUserDetails() {
            try {
                // console.log(id);
                const response = await axios.get(`http://localhost/facebook/api/get-user.php?id=${id}`);
                const userData = response.data.msg;
                console.log(userData);
                // // Populate form fields with fetched user data
                document.getElementById('editName').value = userData.name;
                document.getElementById('editEmail').value = userData.email;
                var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                editProfileModal.show();
            } catch (error) {
                console.error('Error fetching user details:', error);
                alert('Failed to fetch user details.');
            }
        }
        async function saveProfile() {
            var form = document.getElementById('editProfileForm');
            var formData = new FormData(form);
            const res = await axios.post('/facebook/api/update-profile.php', formData);
            console.log(res.data);

            if (res.data.status == 'success') {
                alert('Profile updated successfully!');
                // document.getElementById('editProfileModal').modal('hide');
                document.getElementById("close").click();
                window.location.reload();
            } else {
                alert('Profile update failed!');
            }
        }

        // document.addEventListener("DOMContentLoaded", function() {
        //     loadFriendsList();
        //     loadPendingRequests();
        //     loadRecommendations();
        // });

        async function loadFriendsList() {
            try {
                const response = await axios.get(`http://localhost/facebook/api/get-friends.php?id=${id}&status=accepted`);
                const friends = response.data;
                // console.log(friends);

                const friendsListTableBody = document.getElementById('friendsListTableBody');
                friendsListTableBody.innerHTML = '';

                friends.forEach(friend => {
                    const row = `
                <tr>
                    <td>${friend.name}</td>
                    <td>
                        <button onclick="removeFriend(${friend.id})" class="btn btn-danger">Remove</button>
                    </td>
                </tr>
            `;
                    friendsListTableBody.innerHTML += row;
                });
            } catch (error) {
                console.error('Error loading friends list:', error);
            }
        }

        async function removeFriend(friend_id) {
            try {
                const formData = new FormData();
                formData.append('friend_id', friend_id);

                const response = await axios.post('http://localhost/facebook/api/remove-friend.php', formData);
                console.log(response.data);
                // exit;

                if (response.data.status === 'success') {
                    alert('Friend removed successfully');
                    loadFriendsList();
                    loadRecommendations();
                    loadAcceptRequests();
                    loadPendingRequests();
                } else {
                    console.error('Error removing friend:', response.data.error);
                }
            } catch (error) {
                console.error('Error removing friend:', error);
            }
        }

        // async function loadPendingRequests() {
        //     try {
        //         // Make an axios request to fetch pending requests data
        //         const response = await axios.get(`http://localhost/facebook/api/get-pending-requests.php?id=${id}&status=pending`);
        //         const pendingRequests = response.data;
        //         // console.log(pendingRequests);

        //         // Populate pending requests table
        //         const pendingRequestsTableBody = document.getElementById('pendingRequestsTableBody');
        //         pendingRequestsTableBody.innerHTML = '';
        //         pendingRequests.forEach(request => {
        //             const row = `
        //                 <tr>
        //                     <td>${request.name}</td>
        //                     <td>
        //                         <button class="btn btn-success">Accept</button>
        //                         <button class="btn btn-danger">Reject</button>
        //                     </td>
        //                 </tr>
        //             `;
        //             pendingRequestsTableBody.innerHTML += row;
        //         });
        //     } catch (error) {
        //         console.error('Error loading pending requests:', error);
        //     }
        // }

        // async function loadPendingRequests() {
        //     try {
        //         // Make an axios request to fetch pending requests data
        //         const response = await axios.get(`http://localhost/facebook/api/get-pending-requests.php?id=${id}&status=pending`);
        //         const pendingRequests = response.data;

        //         // Populate pending requests table
        //         const pendingRequestsTableBody = document.getElementById('pendingRequestsTableBody');
        //         pendingRequestsTableBody.innerHTML = '';
        //         pendingRequests.forEach(request => {
        //             const row = `
        //         <tr>
        //             <td>${request.name}</td>
        //             <td>
        //                 <button class="btn btn-success accept-btn" data-id="${request.id}">Accept</button>
        //                 <button class="btn btn-danger reject-btn" data-id="${request.id}">Reject</button>
        //             </td>
        //         </tr>
        //     `;
        //             pendingRequestsTableBody.innerHTML += row;
        //         });

        //         // Add event listeners to accept and reject buttons
        //         document.querySelectorAll('.accept-btn').forEach(button => {
        //             button.addEventListener('click', () => handleFriendRequest(button.dataset.id, 'accepted'));
        //         });
        //         document.querySelectorAll('.reject-btn').forEach(button => {
        //             button.addEventListener('click', () => handleFriendRequest(button.dataset.id, 'rejected'));
        //         });

        //     } catch (error) {
        //         console.error('Error loading pending requests:', error);
        //     }
        // }

        // async function handleFriendRequest(friendId, status) {
        //     console.log(status);

        //     try {
        //         var formdata = new FormData();
        //         formdata.append("user_id", id);
        //         formdata.append("friend_id", friendId);
        //         formdata.append("status", status);

        //         const response = await axios.post('http://localhost/facebook/api/handle-friend-request.php', formdata);
        //         console.log(response.data);
        //         if (response.data.status === 'success') {
        //             alert(`Friend request ${status}`);
        //             loadPendingRequests(); // Refresh the pending requests list
        //             loadFriendsList();
        //             loadRecommendations();
        //         } else {
        //             alert('Failed to update friend request status.');
        //         }
        //     } catch (error) {
        //         console.error(`Error updating friend request status to ${status}:`, error);
        //     }
        // }


        async function loadPendingRequests() {
    try {
        // Make an axios request to fetch pending requests data
        const response = await axios.get(`http://localhost/facebook/api/get-pending-requests.php?id=${id}&status=pending`);
        const pendingRequests = response.data;
        console.log(pendingRequests);
        // exit;

        // Populate pending requests table
        const pendingRequestsTableBody = document.getElementById('pendingRequestsTableBody');
        pendingRequestsTableBody.innerHTML = '';
        pendingRequests.forEach(request => {
            let buttons = '';

            if (request.requester) {
                buttons = `<button class="btn btn-success accept-btn" data-id="${request.id}">Accept</button>`;
            }

            buttons += `<button class="btn btn-danger reject-btn" data-id="${request.id}">Reject</button>`;

            const row = `
                <tr>
                    <td>${request.name}</td>
                    <td>${buttons}</td>
                </tr>
            `;
            pendingRequestsTableBody.innerHTML += row;
        });

        // Add event listeners to accept and reject buttons
        document.querySelectorAll('.accept-btn').forEach(button => {
            button.addEventListener('click', () => handleFriendRequest(button.dataset.id, 'accepted'));
        });
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', () => handleFriendRequest(button.dataset.id, 'rejected'));
        });

    } catch (error) {
        console.error('Error loading pending requests:', error);
    }
}

async function handleFriendRequest(friendId, status) {
    console.log(status);

    try {
        var formdata = new FormData();
        formdata.append("user_id", id);
        formdata.append("friend_id", friendId);
        formdata.append("status", status);

        const response = await axios.post('http://localhost/facebook/api/handle-friend-request.php', formdata);
        // console.log(response.data);
        if (response.data.status === 'success') {
            alert(`Friend request ${status}`);
            loadPendingRequests(); // Refresh the pending requests list
            loadFriendsList();
            loadAcceptRequests();
            loadRecommendations();
        } else {
            alert('Failed to update friend request status.');
        }
    } catch (error) {
        console.error(`Error updating friend request status to ${status}:`, error);
    }
}



async function loadAcceptRequests() {
    try {
        // Make an axios request to fetch accept requests data
        const response = await axios.get(`http://localhost/facebook/api/get-accept-requests.php?id=${id}`);
        const acceptRequests = response.data;
        console.log(acceptRequests);
        // exit;

        // Populate accept requests table
        const acceptRequestsTableBody = document.getElementById('acceptRequestsTableBody');
        acceptRequestsTableBody.innerHTML = '';
        acceptRequests.forEach(request => {
            const row = `
                <tr>
                    <td>${request.name}</td>
                    <td>
                        <button class="btn btn-success accept-btn" data-id="${request.user_id}">Accept</button>
                    </td>
                </tr>
            `;
            acceptRequestsTableBody.innerHTML += row;
        });

        // Add event listeners to accept buttons
        document.querySelectorAll('.accept-btn').forEach(button => {
            button.addEventListener('click', () => handleFriendRequest(button.dataset.id, 'accepted'));
        });

    } catch (error) {
        console.error('Error loading accept requests:', error);
    }
}

async function handleFriendRequest(friendId, status) {
    console.log(status);

    try {
        var formdata = new FormData();
        formdata.append("user_id", id);
        formdata.append("friend_id", friendId);
        formdata.append("status", status);

        const response = await axios.post('http://localhost/facebook/api/handle-friend-request.php', formdata);
        console.log(response.data);
        // exit;
        if (response.data.status === 'success') {
            alert(`Friend request ${status}`);
            loadAcceptRequests(); // Refresh the accept requests list
            loadPendingRequests(); // Refresh the pending requests list
            loadFriendsList(); // Refresh the friends list
            loadRecommendations(); // Refresh the recommendations list
        } else {
            alert('Failed to update friend request status.');
        }
    }  catch (error) {
                console.error('Error loading accepts:', error);
            }
        }


        async function loadRecommendations() {
            try {
                const response = await axios.get(`http://localhost/facebook/api/get-recommendations.php?id=${id}`);
                const recommendations = response.data;

                const recommendationsList = document.getElementById('recommendationsList');
                recommendationsList.innerHTML = '';
                recommendations.forEach(recommendation => {
                    const row = `
                <tr>
                    <td><h5>${recommendation.name}</h5></td>
                    <td><img src="uploads/${recommendation.profile_pic}" alt="${recommendation.name}" style="width:200px"></td>
                    <td><button class="btn btn-primary" onclick="sendFriendRequest(${recommendation.id})">Add Friend</button></td>
                </tr>
            `;
                    recommendationsList.innerHTML += row;
                });
            } catch (error) {
                console.error('Error loading recommendations:', error);
            }
        }

        async function sendFriendRequest(friend_id) {
            try {
                const formData = new FormData();
                formData.append('friend_id', friend_id);

                const response = await axios.post('http://localhost/facebook/api/add-friend-request.php', formData);
                if (response.data.status === 'success') {
                    alert('Friend request sent successfully');
                    loadRecommendations(); // Refresh recommendations list
                    loadPendingRequests();
                    loadAcceptRequests();
                    loadFriendsList();
                } else {
                    console.error('Error sending friend request:', response.data.error);
                }
            } catch (error) {
                console.error('Error sending friend request:', error);
            }
        }


        async function edit_img() {
            const res = await axios.get('/facebook/api/get-user-details.php');
            const user = res.data;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editProfileModal').modal('show');
        }
    </script>
</body>

</html>