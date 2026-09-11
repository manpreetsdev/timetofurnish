@extends('frontend.layouts.user_panel')

@section('panel_content')

@include('frontend.user.wishlist_items')

<!-- =========================================
     PAGINATION
========================================= -->
@if (method_exists($wishlists, 'links'))
<div class="aiz-pagination mt-4 d-flex justify-content-center">
    {{ $wishlists->links() }}
</div>
@endif

@endsection


@section('modal')

<!-- =========================================
     ADD TO CART MODAL
========================================= -->
<div
    class="modal fade"
    id="addToCart"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div
        class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal"
        id="modal-size"
        role="document">

        <div class="modal-content position-relative">

            <div class="c-preloader">
                <i class="fa fa-spin fa-spinner"></i>
            </div>

            <button
                type="button"
                class="close absolute-close-btn"
                data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">
                    &times;
                </span>
            </button>

            <div id="addToCart-modal-body">

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script type="text/javascript">
    /* =========================================
       INSTANT REMOVE FROM WISHLIST (OPTIMISTIC UI)
    ========================================== */
    function removeFromWishlist(id) {
        var $item = $('#wishlist_' + id);
        var $grid = $item.parent();

        // 1. Instant UI Feedback (Fade out & collapse immediately)
        if ($item.length) {
            $item.css({
                'transition': 'all 0.25s ease-out',
                'opacity': '0',
                'transform': 'scale(0.85)'
            });

            setTimeout(function() {
                $item.slideUp(200, function() {
                    $item.remove();
                    showWishlistEmptyState();
                });
            }, 200);
        }

        // 2. Optimistically decrement header wishlist count badge
        var $badge = $('#wishlist .badge');
        if ($badge.length) {
            var currentCount = parseInt($badge.text().trim()) || 0;
            if (currentCount > 1) {
                $badge.text(currentCount - 1);
            } else {
                $badge.remove();
            }
        }

        // 3. Send server request in background
        $.post(
            '{{ route('wishlists.remove') }}',
            {
                _token: '{{ csrf_token() }}',
                id: id
            },
            function(data) {
                if (data) {
                    $('#wishlist').html(data);
                }
                AIZ.plugins.notify(
                    'success',
                    '{{ translate("Item has been removed from wishlist") }}'
                );
            }
        ).fail(function() {
            // Restore element if server call fails
            if ($item.length) {
                $('#wishlist-empty-state').remove();
                $grid.append($item.show().css({'opacity': '1', 'transform': 'none'}));
            }
            AIZ.plugins.notify(
                'danger',
                '{{ translate("Something went wrong, please try again") }}'
            );
        });
    }

    function showWishlistEmptyState() {
        if ($('.wishlist-modern-card').length || $('#wishlist-empty-state').length) return;

        $('.row-cols-1').after(
            '<div id="wishlist-empty-state" class="text-center py-5 w-100">' +
                '<i class="lar la-heart fs-48 opacity-40"></i>' +
                '<h5 class="mt-3">{{ translate("Your wishlist is empty") }}</h5>' +
                '<p class="text-muted">{{ translate("Save your favourite furniture products here.") }}</p>' +
            '</div>'
        );
    }
</script>

@endsection
