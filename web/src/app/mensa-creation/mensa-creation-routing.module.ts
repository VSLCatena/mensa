import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { MensaCreationScreenComponent } from './mensa-creation-screen/mensa-creation-screen.component';

const routes: Routes = [
	{
		path: 'mensa/create',
		component: MensaCreationScreenComponent
	}
];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})
export class MensaCreationRoutingModule {}
