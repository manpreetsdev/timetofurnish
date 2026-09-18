<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\TeamMember;
use App\Support\CustomPageTemplate;
use Illuminate\Support\Facades\Mail;
use App\Models\BusinessSetting;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:add_website_page'])->only(['create', 'store', 'import']);
        $this->middleware(['permission:edit_website_page'])->only(['edit', 'update', 'export']);
        $this->middleware(['permission:delete_website_page'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageBuilderData   = CustomPageTemplate::defaultPayload();
        $fontFamilyOptions = CustomPageTemplate::fontFamilyOptions();

        return view('backend.website_settings.pages.create', compact('pageBuilderData', 'fontFamilyOptions'));
    }

    /**
     * Handle Delivery Partner submission.
     */
    public function submitDeliveryPartner(Request $request)
    {
        $request->validate([
            'company_name'      => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'contact_number'    => 'required|string|max:30',
            'area_coverage'     => 'required|string',
            'services_provided' => 'required|string',
        ]);

        $mailData = [
            'company_name'      => $request->company_name,
            'email'             => $request->email,
            'contact_number'    => $request->contact_number,
            'area_coverage'     => $request->area_coverage,
            'services_provided' => $request->services_provided,
        ];

        try {
            $recipient   = env('CONTACT_ADMIN_EMAIL', 'askus@timetofurnish.com');
            $fromAddress = env('MAIL_FROM_ADDRESS', 'timetofurnish@gmail.com');
            $fromName    = env('MAIL_FROM_NAME', 'Time to Furnish');

            Mail::send('emails.delivery-partner', $mailData, function ($message) use ($recipient, $fromAddress, $fromName) {
                $message->from($fromAddress, $fromName)
                        ->to($recipient)
                        ->subject('New Delivery Partner Request');
            });

            return back()->with('success', translate('Thanks for applying! We have received your details.'));
        } catch (\Exception $e) {
            \Log::error('Delivery partner email error: ' . $e->getMessage());
            return back()->with('error', translate('Email failed to send: ') . $e->getMessage());
        }
    }

    public function DeliveryPartner()
    {
        return view('frontend.become_delivery_partner');
    }

    /**
     * Store a newly created contact form request and notify Admin & Customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit_contact(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',
            'message'    => 'required|string',
        ]);

        try {
            $data = [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'message1'   => $request->message,
            ];

            // Primary target Admin email
            $adminEmail = env('CONTACT_ADMIN_EMAIL', 'manpreetsdev@gmail.com');
            if (empty($adminEmail)) {
                $adminEmail = 'manpreetsdev@gmail.com';
            }

            $fromAddress = env('MAIL_FROM_ADDRESS', 'timetofurnish@gmail.com');
            $fromName    = env('MAIL_FROM_NAME', 'Time to Furnish');
            $fullName    = trim($data['first_name'] . ' ' . $data['last_name']);

            /*
            |--------------------------------------------------------------------------
            | ADMIN EMAIL NOTIFICATION
            |--------------------------------------------------------------------------
            */
            Mail::send(
                'emails.contact_us',
                [
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'email'      => $data['email'],
                    'phone'      => $data['phone'],
                    'message1'   => $data['message1'],
                    'type'       => 'admin',
                ],
                function ($message) use ($request, $adminEmail, $fullName, $fromAddress, $fromName) {
                    $message->from($fromAddress, $fromName)
                        ->to($adminEmail)
                        ->replyTo($request->email, $fullName)
                        ->subject('New Contact Us Inquiry - ' . $fullName);
                }
            );

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER EMAIL CONFIRMATION
            |--------------------------------------------------------------------------
            */
            Mail::send(
                'emails.contact_us',
                [
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'email'      => $data['email'],
                    'phone'      => $data['phone'],
                    'message1'   => $data['message1'],
                    'type'       => 'customer',
                ],
                function ($message) use ($request, $fromAddress, $fromName) {
                    $message->from($fromAddress, $fromName)
                        ->to($request->email)
                        ->subject('Thank You for Contacting Time To Furnish');
                }
            );

            return back()->with(
                'success',
                translate('Thank you for contacting us. We will respond as soon as possible.')
            );

        } catch (\Throwable $e) {
            \Log::error('Contact form email failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->with(
                'error',
                translate('Email Error: ') . $e->getMessage()
            );
        }
    }

    public function store(Request $request)
    {
        $page = new Page;
        $page->title = $request->title;
        $content = $this->buildPageContentPayload($request);

        $slugCandidate = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));

        if (Page::where('slug', $slugCandidate)->first() == null) {
            $page->slug             = $slugCandidate;
            $page->type             = "custom_page";
            $page->content          = $content;
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            $page_translation           = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $content;
            $page_translation->save();

            flash(translate('New page has been created successfully'))->success();
            return redirect()->route('custom-pages.edit', ['id' => $page->slug, 'lang' => env('DEFAULT_LANGUAGE')]);
        }

        flash(translate('Slug has been used already'))->warning();
        return back();
    }

    // Contact us page view
    public function contact_us()
    {
        return view('frontend.contact_us');
    }

    // Career page view
    public function career()
    {
        return view('frontend.career');
    }

    // Career form submission
    public function career_submit(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'role'  => 'required|string|max:255',
            'cv'    => 'required|mimes:pdf,doc,docx|max:2048'
        ]);

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cv'), $filename);
        }

        return back()->with('success', translate('Application submitted successfully'));
    }

    public function become_delivery_partner()
    {
        return view('frontend.become_delivery_partner');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $page_name = $request->page;
        $page = Page::where('slug', $id)->first();
        if ($page != null) {
            $pageBuilderData = CustomPageTemplate::fromContent(
                $page->getTranslation('content', $lang),
                $page->getTranslation('title', $lang)
            );
            $fontFamilyOptions = CustomPageTemplate::fontFamilyOptions();

            if ($page_name == 'home') {
                return view('backend.website_settings.pages.' . get_setting('homepage_select') . '.home_page_edit', compact('page', 'lang'));
            }
            return view('backend.website_settings.pages.edit', compact('page', 'lang', 'pageBuilderData', 'fontFamilyOptions'));
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Retrieve the page by slug (as routes pass slug as $id)
        $page = Page::findOrFail($id);
        $content = $this->buildPageContentPayload($request);
        $slugCandidate = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));

        // Ensure slug uniqueness excluding current page ID
        if (Page::where('id', '!=', $page->id)->where('slug', $slugCandidate)->first() == null) {
            if ($request->slug) {
                $page->slug = $slugCandidate;
            }
            if ($request->lang == env("DEFAULT_LANGUAGE")) {
                $page->title   = $request->title;
                $page->content = $content;
            }
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            \Artisan::call('cache:clear');

            $page_translation           = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $content;
            $page_translation->save();

            flash(translate('Page has been updated successfully'))->success();
            return redirect()->route('custom-pages.edit', ['id' => $page->slug, 'lang' => $request->lang]);
        }

        flash(translate('Slug has been used already'))->warning();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export($id)
    {
        $page = Page::findOrFail($id);

        $data = [
            'title'            => $page->title,
            'slug'             => $page->slug,
            'type'             => $page->type,
            'content'          => $page->content,
            'meta_title'       => $page->meta_title,
            'meta_description' => $page->meta_description,
            'keywords'         => $page->keywords,
            'meta_image'       => $page->meta_image,
            'translations'     => $page->page_translations->map(function ($translation) {
                return [
                    'lang'    => $translation->lang,
                    'title'   => $translation->title,
                    'content' => $translation->content,
                ];
            })->toArray(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $fileName = 'custom-page-' . $page->slug . '-' . date('Y-m-d') . '.json';

        return response($json, 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:json,txt',
        ]);

        try {
            $file = $request->file('import_file');
            $data = json_decode(file_get_contents($file->getRealPath()), true);

            if (!$data || !isset($data['title']) || !isset($data['content'])) {
                flash(translate('Invalid page data file.'))->error();
                return back();
            }

            // Check slug and generate unique slug if it exists
            $slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $data['slug'] ?? $data['title']));
            $originalSlug = $slug;
            $counter = 1;
            while (Page::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $page = new Page;
            $page->title = $data['title'];
            $page->slug = $slug;
            $page->type = $data['type'] ?? 'custom_page';

            $content = $data['content'];
            if (is_array($content)) {
                $content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            $page->content          = $content;
            $page->meta_title       = $data['meta_title'] ?? null;
            $page->meta_description = $data['meta_description'] ?? null;
            $page->keywords         = $data['keywords'] ?? null;
            $page->meta_image       = $data['meta_image'] ?? null;
            $page->save();

            // Handle translations
            if (isset($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $translationData) {
                    $translation = PageTranslation::firstOrNew([
                        'page_id' => $page->id,
                        'lang'    => $translationData['lang']
                    ]);
                    $translation->title = $translationData['title'];

                    $transContent = $translationData['content'];
                    if (is_array($transContent)) {
                        $transContent = json_encode($transContent, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    }
                    $translation->content = $transContent;
                    $translation->save();
                }
            } else {
                // Save default translation
                $translation = PageTranslation::firstOrNew([
                    'page_id' => $page->id,
                    'lang'    => env('DEFAULT_LANGUAGE', 'en')
                ]);
                $translation->title   = $page->title;
                $translation->content = $content;
                $translation->save();
            }

            flash(translate('Page imported successfully'))->success();
            return redirect()->route('website.pages');

        } catch (\Exception $e) {
            flash(translate('Failed to import page: ') . $e->getMessage())->error();
            return back();
        }
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->page_translations()->delete();

        if (Page::destroy($id)) {
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

    public function show_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page != null) {
            return view('frontend.custom_page', compact('page'));
        }
        abort(404);
    }

    public function meet_the_team()
    {
        if (get_setting('team_members_page_status', 0) != 1) {
            abort(404);
        }

        // Dynamic ordering based on admin-configured fields
        $team_members = TeamMember::where('is_active', 1)
            ->orderBy('department_sort_order')
            ->orderBy('sort_order')
            ->orderByRaw('LOWER(department)')
            ->orderByRaw('LOWER(name)')
            ->get();

        return view('frontend.meet_the_team', compact('team_members'));
    }

    public function mobile_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page != null) {
            return view('frontend.m_custom_page', compact('page'));
        }
        abort(404);
    }

    protected function buildPageContentPayload(Request $request): string
    {
        $payload = [
            'page_builder'    => true,
            'template'        => CustomPageTemplate::TEMPLATE_STORY,
            'banner'          => $request->input('builder.banner', []),
            'styles'          => $request->input('builder.styles', []),
            'classic_html'    => '',
            'classic_blocks'  => [],
            'policy_intro'    => '',
            'policy_html'     => '',
            'policy_sections' => [],
            'sections'        => $request->input('builder.sections', []),
        ];

        return CustomPageTemplate::encode($payload, $request->title);
    }

    public function updateSlug(Request $request)
    {
        $request->validate([
            'id'    => 'required|integer|exists:pages,id',
            'slug'  => 'required|string',
            'title' => 'required|string|max:255',
        ]);

        $page = Page::findOrFail($request->id);
        $newSlug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->slug)));

        $existing = Page::where('slug', $newSlug)->where('id', '!=', $page->id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => translate('Slug has already been used.')
            ], 422);
        }

        $page->slug = $newSlug;

        $locale = app()->getLocale();
        if ($locale == env('DEFAULT_LANGUAGE', 'en')) {
            $page->title = $request->title;
        }
        $page->save();

        $page_translation = PageTranslation::firstOrNew([
            'lang'    => $locale,
            'page_id' => $page->id
        ]);
        $page_translation->title = $request->title;
        $page_translation->save();

        \Artisan::call('cache:clear');

        return response()->json([
            'success' => true,
            'slug'    => $newSlug,
            'title'   => $request->title,
            'message' => translate('Page updated successfully.')
        ]);
    }
}
