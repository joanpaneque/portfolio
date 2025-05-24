<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\RecommendTechnologyService;

class PortfolioController extends Controller
{
    public function index(Request $request) {

        $sessionId = $request->session()->getId();
        $canRecommendData = RecommendTechnologyService::canRecommendTechnology(null, $sessionId);
        $lastRecommendedTechnology = RecommendTechnologyService::getLastRecommendedTechnology($sessionId);
        
        return Inertia::render('Portfolio', [
            "canRecommendTechnology" => $canRecommendData["success"],
            "lastRecommendedTechnology" => $lastRecommendedTechnology,
            "timeRemaining" => $canRecommendData["timeRemaining"]
        ]);
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'El nombre es requerido.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.max' => 'El email no puede tener más de 255 caracteres.',
            'subject.required' => 'El asunto es requerido.',
            'subject.max' => 'El asunto no puede tener más de 255 caracteres.',
            'message.required' => 'El mensaje es requerido.',
            'message.max' => 'El mensaje no puede tener más de 2000 caracteres.',
        ]);

        // Here you would typically send an email or save to database
        // For now, we'll just log the contact form submission
        \Log::info('Contact form submitted', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        // You could also send an email here:
        // Mail::to('joanpd0@gmail.com')->send(new ContactFormMail($validated));

        return back()->with('success', 'Mensaje enviado correctamente. Te responderé lo antes posible.');
    }

    public function recommendTechnology(Request $request) {
        $technology = $request->input('technology');
        $sessionId = $request->session()->getId();

        $result = RecommendTechnologyService::recommendTechnology($technology, $sessionId);

        $canRecommendData = RecommendTechnologyService::canRecommendTechnology(null, $sessionId);
        $lastRecommendedTechnology = RecommendTechnologyService::getLastRecommendedTechnology($sessionId);

        return response()->json([
            "success" => $result["success"],
            "canRecommendTechnology" => $canRecommendData["success"],
            "lastRecommendedTechnology" => $lastRecommendedTechnology,
            "timeRemaining" => $canRecommendData["timeRemaining"]
        ]);
    }

    public function undoRecommendation(Request $request) {
        $sessionId = $request->session()->getId();

        $result = RecommendTechnologyService::undoLastRecommendation($sessionId);

        $canRecommendData = RecommendTechnologyService::canRecommendTechnology(null, $sessionId);
        $lastRecommendedTechnology = RecommendTechnologyService::getLastRecommendedTechnology($sessionId);

        return response()->json([
            "success" => $result["success"],
            "message" => $result["message"],
            "canRecommendTechnology" => $canRecommendData["success"],
            "lastRecommendedTechnology" => $lastRecommendedTechnology,
            "timeRemaining" => $canRecommendData["timeRemaining"]
        ]);
    }
}
