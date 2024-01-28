import { Component, Input } from '@angular/core';
import { MenuItem } from 'src/app/common/models/menu-item.model';

@Component({
	selector: 'app-menu-collapse',
	templateUrl: './menu-collapse.component.html',
	styleUrl: './menu-collapse.component.scss'
})
export class MenuCollapseComponent {
	@Input() isCollapsed = true;
	@Input({ required: true }) menuItems: MenuItem[] = [];
}
