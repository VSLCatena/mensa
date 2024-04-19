import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { MensaCreationScreenComponent } from './mensa-create-screen/mensa-creation-screen.component';
import { MensaOverviewScreenComponent } from './mensa-overview-screen/mensa-overview-screen.component';
import { MensaRegisterScreenComponent } from './mensa-register-screen/mensa-register-screen.component';

const routes: Routes = [
	{
		path: 'create',
		component: MensaCreationScreenComponent
	},
	{
		path: ':id',
		component: MensaOverviewScreenComponent
	},
	{
		path: 'register/:id',
		component: MensaRegisterScreenComponent
	}

];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})
export class MensaRoutingModule {}
