<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\BaseController as Controller;
use App\Models\BusinessSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->handleRequest(function () {
            return view('services.email.index');
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function query_notify_email(Request $request)
    {
        try {

            // ✅ Optional validation
            $request->validate([
                'email' => 'nullable|email',
                'to' => 'nullable|email',
                'sender_name' => 'nullable|string|max:20',
            ]);

            $to = $request->to ?: business_setting_by_key('contact_query_receiver_mail');
            $siteName = $request->site_name ?? "Contact Form";
            $sender_name = $request->sender_name ?? env('APP_NAME');

            if (empty($to)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sender mail not found',
                ], 404);
            }

            $currentTime = Carbon::now()
                ->setTimezone('Asia/Kolkata')
                ->format('d-m-Y H:i:s');

            $subject = "{$siteName} | Contact Query | {$currentTime}";

            $ignoreKeys = ['_token', 'site_name', 'to', 'sender_name'];
            $rows = '';

            foreach ($request->all() as $key => $value) {

                if (in_array($key, $ignoreKeys)) {
                    continue;
                }

                if (is_array($value)) {
                    $value = implode(', ', $value);
                }

                $value = $value ?: 'N/A';
                $label = ucwords(str_replace('_', ' ', $key));

                $rows .= "
                <tr>
                    <td style='padding:8px; font-weight:bold;'>{$label}</td>
                    <td style='padding:8px;'>{$value}</td>
                </tr>
            ";
            }

            $html = "
            <div style='font-family:Arial; background:#f4f4f4; padding:20px'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr>
                        <td align='center'>
                            <table width='600' style='background:#fff; padding:20px; border-radius:8px'>
                                <tr>
                                    <td>
                                        <h2>{$siteName}</h2>
                                        <p><strong>Submitted At:</strong> {$currentTime}</p>

                                        <table width='100%' border='1' cellspacing='0' cellpadding='0'>
                                            {$rows}
                                        </table>

                                        <br>
                                        <small>
                                            This email was generated automatically.<br>
                                            © " . date('Y') . " {$siteName}
                                        </small>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        ";

            Mail::html($html, function ($mail) use ($to, $subject, $sender_name) {
                $mail->from(env('MAIL_FROM_ADDRESS'), $sender_name);
                $mail->to($to)->subject($subject);
            });

            // ✅ SUCCESS RESPONSE
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            Log::error('Email Send Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email sending failed',
            ], 500);
        }
    }
}
