<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black/95 bg-opacity-90 z-50 hidden flex items-center justify-center">
    <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center p-4">
        <!-- Close button -->
        <button id="closeModal" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <!-- Previous button -->
        <button id="prevImage"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 hidden">
            <i class="fas fa-chevron-left text-3xl"></i>
        </button>

        <!-- Next button -->
        <button id="nextImage"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10 hidden">
            <i class="fas fa-chevron-right text-3xl"></i>
        </button>

        <!-- Image container -->
        <div class="flex items-center justify-center w-full h-full">
            <img id="modalImage" src="/placeholder.svg" alt="" class="max-w-full max-h-full object-contain">
        </div>

        <!-- Image counter -->
        <div id="imageCounter"
            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-3 py-1 rounded-full hidden">
            <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
        </div>
    </div>
</div>

<!-- Comments Modal -->
<div id="commentsModal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
            <button id="closeCommentsModal" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i class="fas fa-times text-gray-400"></i>
            </button>
        </div>

        <!-- Comments List -->
        <div id="commentsList" class="flex-1 overflow-y-auto p-4 space-y-3 max-h-96">
        </div>

        <!-- Comment Input -->
        <div class="p-4 border-t border-gray-200 flex-shrink-0">
            <!-- Reply indicator section -->
            <div id="replyIndicator" class="hidden mb-3 p-2 bg-red-50 rounded-lg border border-red-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-red-700">
                        <i class="fas fa-reply text-red-500 mr-1"></i>
                        Replying to <span id="replyToUser" class="font-medium"></span>
                    </span>
                    <button id="cancelReply" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-red-500 text-sm"></i>
                </div>
                <div class="flex-1 flex space-x-2">
                    <input type="text" id="modalCommentInput" placeholder="Write a comment..."
                        class="flex-1 bg-gray-100 border-0 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white">
                    <button id="modalSubmitComment"
                        class="bg-red-500 text-white px-4 py-2 rounded-full font-medium hover:bg-red-600 transition-colors">
                        Post
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Post Modal -->
<div id="reportModal" class="fixed inset-0 bg-black/80 bg-opacity-30 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Report Post</h3>
            <button id="closeReportModal" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i class="fas fa-times text-gray-400"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4">
            <p class="text-gray-600 mb-4">Why are you reporting this post?</p>
            <div class="space-y-2">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="radio" name="reportReason" value="spam" class="text-red-500 focus:ring-red-500">
                    <span class="text-gray-700">Spam</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="radio" name="reportReason" value="harassment" class="text-red-500 focus:ring-red-500">
                    <span class="text-gray-700">Harassment or bullying</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="radio" name="reportReason" value="inappropriate"
                        class="text-red-500 focus:ring-red-500">
                    <span class="text-gray-700">Inappropriate content</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="radio" name="reportReason" value="misinformation"
                        class="text-red-500 focus:ring-red-500">
                    <span class="text-gray-700">False information</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="radio" name="reportReason" value="other" class="text-red-500 focus:ring-red-500">
                    <span class="text-gray-700">Other</span>
                </label>
            </div>
            <div class="mt-4">
                <textarea id="reportDescription" placeholder="Additional details (optional)"
                    class="w-full bg-gray-100 border-0 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white resize-none"
                    rows="3"></textarea>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 p-4 border-t border-gray-200">
            <button id="cancelReport" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                Cancel
            </button>
            <button id="submitReport"
                class="bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition-colors">
                Submit Report
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const csrfToken = "{{ csrf_token() }}";
        let currentPostId = null;
        let currentPostImages = [];
        let currentImageIndex = 0;
        // Variables to track reply state
        let replyToCommentId = null;
        let replyToUserName = null;
        let currentReportPostId = null;

        const postsComments = {
            @foreach ($posts as $post)
                {{ $post->id }}: [
                @foreach ($post->comments as $comment)
                        {
                    id: {{ $comment->id }},
                    comment: @json($comment->comment),
                    user_name: @json($comment->user->name ?? 'Anonymous'),
                    created_at: @json($comment->created_at->diffForHumans()),
                    user_avatar: @json($comment->user->avatar ?? null),
                    parent_id: {{ $comment->parent_id ?? 'null' }},
                    replies_count: {{ $comment->replies ? $comment->replies->count() : 0 }}
                        }@if(!$loop->last), @endif
                @endforeach
                ]@if(!$loop->last),@endif
            @endforeach
                                                                        };

        $(document).ready(function () {
            $('.like-button').on('click', function () {
                const postId = $(this).attr('data');

                $.ajax({
                    url: "{{ route('common.post.like') }}",
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    },
                    data: JSON.stringify({
                        post_id: postId
                    }),
                    success: function (data) {
                        const $button = $(`[data="${postId}"].like-button`);
                        const $icon = $button.find('i');
                        const $span = $button.find('span');
                        const $likesCount = $(`#likes-${postId}`);

                        $icon.removeClass().addClass('fas fa-heart text-red-500');
                        $span.text('Liked').removeClass().addClass('text-red-500 font-medium');

                        const currentCount = parseInt($likesCount.text());
                        const newCount = currentCount + 1;
                        $likesCount.text(newCount + (newCount === 1 ? ' like' : ' likes'));
                    },
                    error: function () {
                        const $button = $(`[data="${postId}"].like-button`);
                        const $icon = $button.find('i');
                        const $span = $button.find('span');
                        const $likesCount = $(`#likes-${postId}`);

                        $icon.removeClass().addClass('far fa-heart text-gray-600');
                        $span.text('Like').removeClass().addClass('text-gray-600 font-medium');

                        const currentCount = parseInt($likesCount.text());
                        const newCount = currentCount - 1;
                        $likesCount.text(newCount + (newCount === 1 ? ' like' : ' likes'));
                    }
                });
            });

            $('.comment-button').on('click', function () {
                currentPostId = $(this).attr('data');
                loadComments(currentPostId);
                openCommentsModal();
            });

            function loadComments(postId) {
                const comments = postsComments[postId] || [];
                displayComments(comments);
            }

            function displayComments(comments) {
                const $commentsList = $('#commentsList');
                $commentsList.empty();

                if (comments.length === 0) {
                    $commentsList.html('<p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>');
                    return;
                }

                const mainComments = comments.filter(comment => !comment.parent_id);
                const replies = comments.filter(comment => comment.parent_id);

                mainComments.forEach(comment => {
                    const commentElement = createCommentElement(comment);
                    $commentsList.append(commentElement);

                    const commentReplies = replies.filter(reply => reply.parent_id === comment.id);
                    if (commentReplies.length > 0) {
                        if (commentReplies.length === 1) {
                            // Show single reply directly
                            const replyElement = createReplyElement(commentReplies[0]);
                            $commentsList.append(replyElement);
                        } else {
                            // Create hidden replies container for multiple replies
                            const repliesContainer = $(`<div class="replies-container hidden" data-comment-id="${comment.id}"></div>`);
                            commentReplies.forEach(reply => {
                                const replyElement = createReplyElement(reply);
                                repliesContainer.append(replyElement);
                            });
                            $commentsList.append(repliesContainer);
                        }
                    }
                });
            }

            function createCommentElement(comment) {
                const avatarHtml = comment.user_avatar
                    ? `<img src="${comment.user_avatar}" alt="${comment.user_name}" class="w-8 h-8 rounded-full object-cover">`
                    : `<div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                                                         <i class="fas fa-user text-red-500 text-sm"></i>
                                                                                       </div>`;

                let replyCountHtml = '';
                if (comment.replies_count > 1) {
                    replyCountHtml = `<button class="view-replies-btn text-sm text-red-600 hover:text-red-700 font-medium" data-comment-id="${comment.id}">
                                                                                         <i class="fas fa-chevron-down mr-1"></i>View all ${comment.replies_count} replies
                                                                                       </button>`;
                }

                return $(`
                                                                                    <div class="flex space-x-3 mb-4">
                                                                                        ${avatarHtml}
                                                                                        <div class="flex-1">
                                                                                            <div class="bg-gray-100 rounded-lg px-3 py-2">
                                                                                                <p class="font-medium text-sm text-gray-900">${comment.user_name}</p>
                                                                                                <p class="text-gray-800">${comment.comment}</p>
                                                                                            </div>
                                                                                            <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                                                                                <span>${comment.created_at}</span>
                                                                                                <button class="reply-btn hover:text-gray-700" data-comment-id="${comment.id}" data-user-name="${comment.user_name}">Reply</button>
                                                                                            </div>
                                                                                            ${replyCountHtml}
                                                                                        </div>
                                                                                    </div>
                                                                                `);
            }

            function createReplyElement(reply) {
                const avatarHtml = reply.user_avatar
                    ? `<img src="${reply.user_avatar}" alt="${reply.user_name}" class="w-7 h-7 rounded-full object-cover">`
                    : `<div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                                                                                         <i class="fas fa-user text-gray-500 text-xs"></i>
                                                                                       </div>`;

                return $(`
                                                                                    <div class="flex space-x-3 ml-11 mb-3">
                                                                                        ${avatarHtml}
                                                                                        <div class="flex-1">
                                                                                            <div class="bg-gray-50 rounded-lg px-3 py-2 border border-gray-200">
                                                                                                <p class="font-medium text-sm text-gray-900">${reply.user_name}</p>
                                                                                                <p class="text-gray-700 text-sm">${reply.comment}</p>
                                                                                            </div>
                                                                                            <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                                                                                <span>${reply.created_at}</span>
                                                                                                <button class="reply-btn hover:text-gray-700" data-comment-id="${reply.parent_id}" data-user-name="${reply.user_name}">Reply</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                `);
            }

            function openCommentsModal() {
                $('#commentsModal').removeClass('hidden');
                $('body').css('overflow', 'hidden');
                $('#modalCommentInput').focus();
            }

            function closeCommentsModalFunc() {
                $('#commentsModal').addClass('hidden');
                $('body').css('overflow', 'auto');
                $('#modalCommentInput').val('');
                // Reset reply state when closing modal
                cancelReply();
            }

            function submitComment() {
                const comment = $('#modalCommentInput').val().trim();
                if (!comment || !currentPostId) return;

                console.log('Submitting comment:', {
                    comment: comment,
                    post_id: currentPostId,
                    parent_id: replyToCommentId,
                    csrf_token: csrfToken
                });

                // Include parent_id in the request data
                const requestData = {
                    comment: comment,
                    post_id: currentPostId
                };

                if (replyToCommentId) {
                    requestData.parent_id = replyToCommentId;
                }

                console.log('Request data:', requestData);

                $.ajax({
                    url: "{{ route('common.post.comment') }}",
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: JSON.stringify(requestData),
                    success: function (data) {
                        console.log('Comment posted successfully:', data);

                        // Updated comment display for replies
                        const displayComment = replyToCommentId ? `@${replyToUserName} ${comment}` : comment;

                        const newCommentData = {
                            id: data.id || Date.now(),
                            comment: displayComment,
                            user_name: 'you',
                            created_at: 'Just now',
                            user_avatar: null,
                            parent_id: replyToCommentId,
                            replies_count: 0
                        };

                        // Add to local comments data
                        if (!postsComments[currentPostId]) {
                            postsComments[currentPostId] = [];
                        }
                        postsComments[currentPostId].push(newCommentData);

                        // Remove "no comments" message if it exists
                        $('#commentsList p.text-gray-500').remove();

                        if (replyToCommentId) {
                            // This is a reply - create reply element and append after parent comment
                            const newReply = createReplyElement(newCommentData);

                            // Find or create replies container for this parent comment
                            let repliesContainer = $(`.replies-container[data-comment-id="${replyToCommentId}"]`);

                            if (repliesContainer.length === 0) {
                                // Create new replies container if it doesn't exist
                                const parentCommentElement = $(`.reply-btn[data-comment-id="${replyToCommentId}"]`).closest('.flex.space-x-3.mb-4');
                                repliesContainer = $(`<div class="replies-container" data-comment-id="${replyToCommentId}"></div>`);
                                parentCommentElement.after(repliesContainer);
                            }

                            repliesContainer.append(newReply);

                            // Show replies container if it's hidden and we now have multiple replies
                            const totalReplies = repliesContainer.children().length;
                            if (totalReplies === 1) {
                                // First reply - show it directly, no button needed
                                repliesContainer.removeClass('hidden');
                            } else if (totalReplies === 2) {
                                // Second reply - hide container and add view button
                                repliesContainer.addClass('hidden');
                                const parentCommentElement = $(`.reply-btn[data-comment-id="${replyToCommentId}"]`).closest('.flex.space-x-3.mb-4');
                                const viewRepliesHtml = `<button class="view-replies-btn text-sm text-red-600 hover:text-red-700 font-medium" data-comment-id="${replyToCommentId}"><i class="fas fa-chevron-down mr-1"></i>View all ${totalReplies} replies</button>`;
                                parentCommentElement.find('.flex.items-center.space-x-4.mt-1').after(viewRepliesHtml);
                            } else {
                                // More replies - update button text
                                const viewRepliesBtn = $(`.view-replies-btn[data-comment-id="${replyToCommentId}"]`);
                                if (repliesContainer.hasClass('hidden')) {
                                    viewRepliesBtn.html(`<i class="fas fa-chevron-down mr-1"></i>View all ${totalReplies} replies`);
                                } else {
                                    viewRepliesBtn.html(`<i class="fas fa-chevron-up mr-1"></i>Hide ${totalReplies} replies`);
                                }
                            }
                        } else {
                            // Regular comment
                            const newComment = createCommentElement(newCommentData);
                            $('#commentsList').append(newComment);
                        }

                        // Scroll to bottom
                        const commentsList = document.getElementById('commentsList');
                        commentsList.scrollTop = commentsList.scrollHeight;

                        // Update comment count in main feed
                        const $commentCount = $(`#comment-${currentPostId}`);
                        const currentCount = parseInt($commentCount.text());
                        $commentCount.text(currentCount + 1);

                        // Clear input and reset reply state
                        $('#modalCommentInput').val('');
                        cancelReply();
                    },
                    error: function (xhr, status, error) {
                        console.log('AJAX Error Details:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });

                        alert('Failed to post comment. Check console for details.');
                    }
                });
            }

            $('#modalSubmitComment').on('click', function () {
                submitComment();
            });

            $('#modalCommentInput').on('keypress', function (e) {
                if (e.which === 13) { // Enter key
                    submitComment();
                }
            });

            // Reply button click handler
            $(document).on('click', '.reply-btn', function () {
                const commentId = $(this).data('comment-id');
                const userName = $(this).data('user-name');

                replyToCommentId = commentId;
                replyToUserName = userName;

                // Show reply indicator
                $('#replyToUser').text(userName);
                $('#replyIndicator').removeClass('hidden');

                // Update placeholder text
                $('#modalCommentInput').attr('placeholder', `Reply to ${userName}...`).focus();
            });

            // Cancel reply functionality
            $('#cancelReply').on('click', function () {
                cancelReply();
            });

            function cancelReply() {
                replyToCommentId = null;
                replyToUserName = null;
                $('#replyIndicator').addClass('hidden');
                $('#modalCommentInput').attr('placeholder', 'Write a comment...');
            }

            $('#closeCommentsModal').on('click', closeCommentsModalFunc);

            $('#commentsModal').on('click', function (e) {
                if (e.target === this) {
                    closeCommentsModalFunc();
                }
            });

            $(document).on('keydown', function (e) {
                if (e.which === 27 && !$('#commentsModal').hasClass('hidden')) { // Escape key
                    closeCommentsModalFunc();
                }
            });

            // Post options dropdown functionality
            $('.post-options-btn').on('click', function (e) {
                e.stopPropagation();
                const postId = $(this).data('post-id');
                const dropdown = $(`.post-options-dropdown[data-post-id="${postId}"]`);

                // Close all other dropdowns
                $('.post-options-dropdown').not(dropdown).addClass('hidden');

                // Toggle current dropdown
                dropdown.toggleClass('hidden');
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function () {
                $('.post-options-dropdown').addClass('hidden');
            });

            // Prevent dropdown from closing when clicking inside it
            $('.post-options-dropdown').on('click', function (e) {
                e.stopPropagation();
            });

            // Report post functionality
            $('.report-post-btn').on('click', function () {
                currentReportPostId = $(this).data('post-id');
                $('.post-options-dropdown').addClass('hidden');
                openReportModal();
            });

            function openReportModal() {
                $('#reportModal').removeClass('hidden');
                $('body').css('overflow', 'hidden');
                // Reset form
                $('input[name="reportReason"]').prop('checked', false);
                $('#reportDescription').val('');
            }

            function closeReportModal() {
                $('#reportModal').addClass('hidden');
                $('body').css('overflow', 'auto');
                currentReportPostId = null;
            }

            $('#closeReportModal, #cancelReport').on('click', closeReportModal);

            $('#reportModal').on('click', function (e) {
                if (e.target === this) {
                    closeReportModal();
                }
            });

            $('#submitReport').on('click', function (e) {
                const reason = $('input[name="reportReason"]:checked').val();
                const description = $('#reportDescription').val().trim();

                const showToast = (type, message) => {
                    const toast = $(`
                                                                                            <div class="fixed top-4 right-4 bg-${(type == "success") ? "green" : "red"}-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
                                                                                                <div class="flex items-center space-x-2">
                                                                                                    <i class="fas fa-check-circle"></i>
                                                                                                    <span>${message}</span>
                                                                                                </div>
                                                                                            </div>
                                                                                        `);
                    $('body').append(toast);
                    setTimeout(() => {
                        toast.fadeOut(300, function () {
                            $(this).remove();
                        });
                    }, 3000);

                }

                if (!reason) {
                    alert('Please select a reason for reporting this post.');
                    return;
                }

                console.log('  Submitting report:', {
                    post_id: currentReportPostId,
                    reason: reason,
                    description: description
                });

                if (reason == "other" && description == "") {
                    showToast("error", "Please Enter Description");
                    return;
                }

                $.ajax({
                    url: "{{ route('common.post.report') }}",
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: JSON.stringify({
                        post_id: currentReportPostId,
                        reason: reason,
                        report_description: description
                    }),
                    success: function (data) {
                        console.log('  Report submitted successfully:', data);
                        closeReportModal();

                        // Show success message
                        showToast("success", "Post reported successfully")
                    },
                    error: function (xhr, status, error) {
                        console.log('  Report submission error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        showToast("error", xhr.responseJSON.message);

                        // alert('Failed to submit report. Please try again.');
                    }
                });
            });

            // Close report modal with Escape key
            $(document).on('keydown', function (e) {
                if (e.which === 27 && !$('#reportModal').hasClass('hidden')) {
                    closeReportModal();
                }
            });

            // Image modal functionality
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const closeModal = document.getElementById('closeModal');
            const prevButton = document.getElementById('prevImage');
            const nextButton = document.getElementById('nextImage');
            const imageCounter = document.getElementById('imageCounter');
            const currentIndexSpan = document.getElementById('currentImageIndex');
            const totalImagesSpan = document.getElementById('totalImages');

            // Open modal when image is clicked
            document.querySelectorAll('.image-modal-trigger').forEach(trigger => {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    const postId = trigger.getAttribute('data-post-id');
                    const imageIndex = parseInt(trigger.getAttribute('data-image-index'));

                    // Get all images for this post
                    const postImages = document.querySelectorAll(`[data-post-id="${postId}"].image-modal-trigger`);
                    currentPostImages = Array.from(postImages).map(img => img.src);
                    currentImageIndex = imageIndex;

                    openModal();
                });
            });

            function openModal() {
                modalImage.src = currentPostImages[currentImageIndex];
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Update navigation buttons
                updateNavigation();

                // Update counter
                if (currentPostImages.length > 1) {
                    currentIndexSpan.textContent = currentImageIndex + 1;
                    totalImagesSpan.textContent = currentPostImages.length;
                    imageCounter.classList.remove('hidden');
                } else {
                    imageCounter.classList.add('hidden');
                }
            }

            function closeModalFunc() {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function updateNavigation() {
                if (currentPostImages.length > 1) {
                    prevButton.classList.remove('hidden');
                    nextButton.classList.remove('hidden');
                } else {
                    prevButton.classList.add('hidden');
                    nextButton.classList.add('hidden');
                }
            }

            function showPrevImage() {
                if (currentImageIndex > 0) {
                    currentImageIndex--;
                    modalImage.src = currentPostImages[currentImageIndex];
                    currentIndexSpan.textContent = currentImageIndex + 1;
                }
            }

            function showNextImage() {
                if (currentImageIndex < currentPostImages.length - 1) {
                    currentImageIndex++;
                    modalImage.src = currentPostImages[currentImageIndex];
                    currentIndexSpan.textContent = currentImageIndex + 1;
                }
            }

            // Event listeners
            closeModal.addEventListener('click', closeModalFunc);
            prevButton.addEventListener('click', showPrevImage);
            nextButton.addEventListener('click', showNextImage);

            // Close modal when clicking outside the image
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModalFunc();
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (!modal.classList.contains('hidden')) {
                    switch (e.key) {
                        case 'Escape':
                            closeModalFunc();
                            break;
                        case 'ArrowLeft':
                            showPrevImage();
                            break;
                        case 'ArrowRight':
                            showNextImage();
                            break;
                    }
                }
            });

            $(document).on('click', '.view-replies-btn', function () {
                const commentId = $(this).data('comment-id');
                const repliesContainer = $(`.replies-container[data-comment-id="${commentId}"]`);
                const button = $(this);

                if (repliesContainer.hasClass('hidden')) {
                    // Show replies
                    repliesContainer.removeClass('hidden');
                    const replyCount = repliesContainer.children().length;
                    button.html(`<i class="fas fa-chevron-up mr-1"></i>Hide ${replyCount} replies`);
                } else {
                    // Hide replies
                    repliesContainer.addClass('hidden');
                    const replyCount = repliesContainer.children().length;
                    button.html(`<i class="fas fa-chevron-down mr-1"></i>View all ${replyCount} replies`);
                }
            });

            $('.follow-button').on('click', function () {
                const ngoId = $(this).data('ngo-id');
                const $button = $(this);

                $.ajax({
                    url: "{{route('common.ngo.follow') }}",
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: JSON.stringify({
                        ngo_id: ngoId
                    }),
                    success: function (data) {
                        if (data.following) {
                            $button.html('Following');
                        } else {
                            $button.html('Follow');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('Follow error:', error);
                        alert('Failed to follow/unfollow user. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush