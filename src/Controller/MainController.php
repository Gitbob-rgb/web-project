{% extends 'base.html.twig' %}

{% block title %}À propos{% endblock %}

{% block body %}
    <div class="container">
        <div class="about-content" style="max-width: 800px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h1>À propos</h1>
            <p>Ce site a été créé en 2022 dans le but de faciliter la publication et la recherche d'offres de stage. Notre plateforme permet de mettre en relation les entreprises et les étudiants à la recherche d'opportunités professionnelles.</p>
            
            <p>Notre mission est de simplifier le processus de recherche de stage en offrant une interface intuitive et efficace pour tous les utilisateurs.</p>
        </div>
    </div>
{% endblock %}
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/about', name: 'about')]
public function about(): Response
{
    return $this->render('about.html.twig');
}