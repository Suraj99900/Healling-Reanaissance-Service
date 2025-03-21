@include('CDN_Header')
@include('navbar')
@php
$sessionManager = new \App\Models\SessionManager();
$iUserId = $sessionManager->iUserID;
@endphp

<div class="main-content">
    <section class="service section " id="service">
        <div class="container">
            <div class="row">
                <div class="section-title padd-15">
                    <h2>Category Videos</h2>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row" id="categoryGrid">
                    <!-- Categories will be dynamically inserted here -->
                </div>
            </div>
    </section>
</div>


@include('CDN_Footer')

<script>

    $(document).ready(function () {

        // Fetch categories and display them
        fetchCategoryByUserId();

        function fetchCategoryByUserId() {
            $.ajax({
                url: "/api/video-categories/{{ $iUserId }}/user", // Adjust the endpoint as needed
                method: "GET",
                success: function (response) {
                    if (response.status == 200) {
                        console.log(response.body);

                        displayCategories(response.body);
                    } else {
                        $("#alertMessage").removeClass("d-none").addClass("alert-danger").text(response.data.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to fetch categories!";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $("#alertMessage").removeClass("d-none").addClass("alert-danger").text(errorMessage);
                }
            });
        }

        function displayCategories(categories) {
            const categoryGrid = $("#categoryGrid");
            categoryGrid.empty(); // Clear any existing categories

            categories.forEach(category => {
                const categoryCard = `
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h3 class="card-title">${category.name}</h3>
                                            <p class="card-text" style="font-size: 0.7rem;">${limitWords(category.description, 20)}</p>
                                            <a href="/videos/${category.id}" class="btnWAN btn-primary btn-lg" style="width:8rem;">View Videos</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                categoryGrid.append(categoryCard);
            });
        }

        function limitWords(text, limit) {
            const words = text.split(" ");
            if (words.length > limit) {
                return words.slice(0, limit).join(" ") + "...";
            }
            return text;
        }
    });
</script>