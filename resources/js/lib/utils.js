// Vérifie si l'URL courante correspond à l'URL de l'onglet
export function urlIsActive(href, currentUrl) {
  if (!href || !currentUrl) return false;
  // Compare sans les paramètres de requête
  const cleanHref = href.split('?')[0];
  const cleanCurrent = currentUrl.split('?')[0];
  return cleanHref === cleanCurrent || cleanCurrent.startsWith(cleanHref + '/');
}

// Ajoute une classe active orange si actif
export function sidebarActiveClass(isActive) {
  return isActive ? 'bg-orange-100 text-orange-600 font-bold' : '';
}
