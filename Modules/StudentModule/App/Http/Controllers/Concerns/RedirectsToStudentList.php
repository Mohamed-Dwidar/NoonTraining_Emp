<?php

namespace Modules\StudentModule\App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

/**
 * Keep the students list's filters + page after delete (single or bulk).
 * Used by the Admin and Employee student controllers.
 */
trait RedirectsToStudentList {

    /**
     * Redirect to the list URL the delete form was submitted from (hidden `redirect_to`).
     * Only URLs of this portal's students list are accepted (no open redirect).
     */
    protected function redirectToStudentList(Request $request, string $indexRoute) {
        $index = route($indexRoute);
        $to    = (string) $request->input('redirect_to', '');

        $isList = $to === $index || str_starts_with($to, $index . '?');

        return redirect($isList ? $to : $index);
    }

    /**
     * If the requested page is now past the last page (its rows were deleted),
     * return a redirect to the last page with the same filters; otherwise null.
     */
    protected function redirectIfPageOutOfRange(Request $request, $paginator) {
        if ($paginator->isNotEmpty() || $paginator->currentPage() <= 1) {
            return null;
        }

        // Use the real query string — the employee index merges forced filters into $request.
        parse_str((string) $request->server('QUERY_STRING'), $query);
        $query['page'] = max(1, $paginator->lastPage());

        return redirect($request->url() . '?' . http_build_query($query));
    }
}
