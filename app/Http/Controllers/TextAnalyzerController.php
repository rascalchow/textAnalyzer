<?php

namespace App\Http\Controllers;

use App\Services\ChatGPTService;
use Illuminate\Http\Request;

class TextAnalyzerController extends Controller
{
    /**
     * Show the text analyzer form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Handle the form submission and perform text analysis.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function analyze(Request $request)
    {
        // Validate the form input 'textInput' to ensure it is required and of type string
        $request->validate([
            'textInput' => 'required|string',
        ]);

        $text = $request->input('textInput');

        // Prompt for ChatGPT, concatenating a request for summarization with the input text
        $prompt = "Please summarize the following text in 100 words or less." . $text;

        $chatGPTService = new ChatGPTService;
        // Ask ChatGPT to summarize text
        $summary = $chatGPTService->askToChatGpt($prompt);

        // Collect all form data and add the summary to this data
        $data = $request->all();
        $data['summary'] = $summary;

        return view('summary', compact('data'));
    }
}
