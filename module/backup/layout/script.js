/*
 * Copyright (c) 2006-2008 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 */

// ---------------------------------------------------------------------------
// toggle all auction checkboxes
// ---------------------------------------------------------------------------
function BackupToggleAuctions ( checked ) {
  $('backupform').getInputs('checkbox').each(function(El) {
    if ((El.name == 'auction[]') && !El.disabled) {
      El.checked = checked;
      ToggleClass('tr_'+El.value, 'selected', checked);
    }
  });
}

