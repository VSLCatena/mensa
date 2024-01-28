import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
	{ path: '', redirectTo: '/home', pathMatch: 'full' },
	{
		path: 'home',
		loadChildren: () => import('./modules/home/home.module').then(m => m.HomeModule)
	},
	{
		path: 'mensa',
		loadChildren: () =>
			import('./modules/mensa/mensa.module').then(m => m.MensaModule)
	},
	{
		path: 'faq',
		loadChildren: () =>
			import('./modules/faq/faq.module').then(m => m.FaqModule)
	}
];

@NgModule({
	imports: [RouterModule.forRoot(routes)],
	exports: [RouterModule]
})
export class AppRoutingModule {}
