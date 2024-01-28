import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FaqScreenComponent } from './faq-screen/faq-screen.component';

const routes: Routes = [
	{
		path: '',
		component: FaqScreenComponent
	}
];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})
export class FaqRoutingModule {}
