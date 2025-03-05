$(document).ready(() => {
    if (iId) {
        fetchAllBlogRecordById(iId);
    } else {
        fetchAllBlogRecord();
    }

    $('#idAddBlogSubmit').on("click", () => {
        var aData = tinymce.get('BlogTextAreaId').getContent();
        var sTitle = $('#BlogTitleId').val();
        // Make another Ajax request for session.php
        $.ajax({
            url: "addBlogPost",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('#csrfid').val()
            },
            data: {
                "authorName": sUserName,
                "content": aData,
                "title": sTitle
            },
            dataType: "json",
            success: function (response) {
                if (response.status === 'success') {
                    responsePop('Success', response.message, 'success', 'ok');
                    setTimeout(() => {
                        window.location.href = "/BlogManage";
                    }, 500);
                } else {
                    responsePop('Error', response.message, 'error', 'ok');
                }

            },
            error: function (error) {
                // Handle Ajax error for session.php
                responsePop('Error', 'Error on server', 'error', 'ok');
            }
        });
    });
});



function fetchAllBlogRecord() {
    $.ajax({
        url: "/fetchAllPosts",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status === 'success') {
                populateDataTable('blogManageTable', response.data);
            } else {
                responsePop('Error', response.message, 'error', 'ok');
            }

        },
        error: function (error) {
            // Handle Ajax error for session.php
            responsePop('Error', 'Error on server', 'error', 'ok');
        }
    });
}

function fetchAllBlogRecordById(iId) {

    $.ajax({
        url: "/fetchPostById/" + iId,
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status === 'success') {
                var aData = response.data;
                console.log(aData.blog_data);
                $('#blogTitleId').text(aData.title);
                $('#BlogHeadingTitleId').text(aData.title);
                $('#blogContentId').html(aData.blog_data);
            } else {
                responsePop('Error', response.message, 'error', 'ok');
            }

        },
        error: function (error) {
            // Handle Ajax error for session.php
            responsePop('Error', 'Error on server', 'error', 'ok');
        }
    });
}

function populateDataTable(tableId, data) {
    $(`#${tableId}`).DataTable({
        data: data,
        destroy: true,
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `<span class="center-flex admin-text" style='font-size: 18px;'>${meta.row + meta.settings._iDisplayStart + 1}</span>`;
                }
            },
            {
                data: 'title',
                render: function (data, type, row, meta) {
                    return `<span class="center-flex admin-text" style='font-size: 18px;'>${data.toUpperCase()}</span>`;
                }
            },
            {
                data: 'author_name',
                render: function (data, type, row, meta) {
                    return `<span class="center-flex admin-text" style='font-size: 18px;'>${data.toUpperCase()}</span>`;
                }
            },
            {
                data: 'status',
                render: function (data, type, row, meta) {
                    console.log(data)
                    if (data == '1') {
                        return `<div class="center-flex"> 
                                    <span class="${row.id}-text updateBlog p-2" style='color: red; font-size: 15px; cursor: pointer;'><i class="${row.id}-text fa-solid fa-trash"></i></span>
                                    <span class="${row.id}-text updateBlog p-2" style='color: gray; font-size: 15px; cursor: pointer;'><i class="${row.id}-text fa-solid fa-pen"></i></span>
                                </div>`;
                    }
                }
            }
        ]
    });
}

$(document).on('click', '.updateBlog', (e) => {
    const classList = e.target.classList;
    for (let className of classList) {
        if (className.endsWith("-text")) {
            BlogId = className.split("-")[0];
            break;
        }
    }

    if (BlogId) {
        const action = e.target.classList[2];
        if (action === 'fa-trash') {
            deleteRecord(BlogId);
        } else if (action === 'fa-pen') {
            // Handle update action
            // fetchAllBlogRecordById(BlogId);
            window.location.href = "updateBlog/"+BlogId;
        }
    }
});



function deleteRecord(BlogId) {
    $.ajax({
        url: "/deletePost/" + BlogId,
        method: "DELETE",
        headers:{
            'X-CSRF-TOKEN': $('#csrfid').val()
        },
        dataType: "json",
        success: function (aData) {

            if (aData.status === 'success') {
                fetchAllBlogRecord();
                responsePop('Success', aData.message, 'success', 'ok');
            } else {
                responsePop('Error', 'Server Error', 'error', 'ok');
            }
        },
        error: function (error) {
            // Handle Ajax error for session.php
            responsePop('Error', 'Server Error', 'error', 'ok');
        }
    });
}