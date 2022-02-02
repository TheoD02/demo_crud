<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Base de la route pour chaque méthode du controlleur
#[Route('/product')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em Permet d'effectuer la communication avec la base de données pour persister, mettre à jour, delete, afficher
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Affiche de la liste des articles
     */
    #[Route('/', name: 'product_index', methods: ['GET'])] // Route et nom, plus possibilité de rajouté des conditions comme pouvoir y accéder qu'en méthode GET
    public function index(ProductRepository $productRepository): Response
    {
        // Rendre la vu index, Envoyer tous les produits dans la vu avec findAll
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * Affiche un formulaire de création, le valide et persist l'entité
     * @param Request $request Contient toutes les informations concernant la requête en cours (Headers, Method HTTP, Paramètres GET, Données POST etc...)
     */
    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        // Initialisation du formulaire via son FormType, il crée le mapping du formulaire pour générer les champs en HTML
        $form = $this->createForm(ProductType::class);

        // Va gérer les données dans la requête pour les retransmettre sous forme d'entités
        $form->handleRequest($request);

        // Si le bouton submit à bien était pressé et que les conditions demandé sont bien respectée alors
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données du formulaire sous forme d'entité
            $product = $form->getData();

            // On persist notre entité puis flush pour sauvegarder en base de données.
            $this->em->persist($product);
            $this->em->flush();

            // On indique à l'utilisateur un message que le produit à bien été ajouté
            $this->addFlash('success', 'Produit ajouté avec succès !');

            // Redirection vers la liste des produits
            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        // On rend la vu du formulaire, puis on passe en paramètres le formulaire
        return $this->renderForm('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Affiche un formulaire de création, le valide et persist l'entité
     */
    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Affiche un formulaire de création, le valide et persist l'entité
     * @param Request $request Contient toutes les informations concernant la requête en cours (Headers, Method HTTP, Paramètres GET, Données POST etc...)
     * @param Product $product Correspond à l'entité que l'on souhaite modifier elle est retrouver grâce à l'id passer dans la route {id}, symfony arrive à comprendre que l'on souhaite récupérer le produit via son ID
     */
    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product): Response
    {
        // Initialisation du formulaire via son FormType, il crée le mapping du formulaire pour générer les champs en HTML avec les données du produit que l'on récupère via les paramètres
        $form = $this->createForm(ProductType::class, $product);
        // Va gérer les données dans la requête pour les retransmettre sous forme d'entités
        $form->handleRequest($request);

        // Si le bouton submit à bien était pressé et que les conditions demandées sont bien respectée alors
        if ($form->isSubmitted() && $form->isValid()) {
            // L'entité existe déjà, pas besoin de persist.
            // On doit juste flush pour mettre à jour les données en base de données
            $this->em->flush();

            // On indique un jolie message de confirmation à notre utilisateur
            $this->addFlash('success', 'Produit mis à jour avec succès !');

            // Redirection vers la liste des produits
            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        // On rend la vu du formulaire, puis on passe en paramètres le formulaire et le produit pour pouvoir remplir les champs avec les données existantes
        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Affiche un formulaire de création, le valide et persist l'entité
     */
    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product): Response
    {
        // On vérifie que le token est CSRF de sécurité est valide
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            // On supprime l'entité puis on flush pour supprimer le produit de la base de données
            $this->em->remove($product);
            $this->em->flush();
            // Toujours un petit message
            $this->addFlash('success', 'Produit supprimé avec succès !');
        }

        // Et toujours une redirection sur la liste
        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
}
